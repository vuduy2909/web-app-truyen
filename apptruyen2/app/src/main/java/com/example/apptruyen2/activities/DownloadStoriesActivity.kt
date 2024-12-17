package com.example.apptruyen2.activities

import android.annotation.SuppressLint
import android.os.Bundle
import android.view.LayoutInflater
import android.view.MenuItem
import android.view.View
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.apptruyen2.R
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.chapter_download.Chapter
import com.example.apptruyen2.data.chapter_download.ChapterDownloadResponse
import com.example.apptruyen2.data.showstory.ShowStoryResponse
import com.example.apptruyen2.databinding.ActivityDownloadStoriesBinding
import com.example.apptruyen2.helper.Util
import com.example.apptruyen2.model.Story
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.*
import com.example.apptruyen2.model.Chapter as ChapterLocal

class DownloadStoriesActivity : AppCompatActivity() {
  private lateinit var binding: ActivityDownloadStoriesBinding

  private val gson = Gson()

  private lateinit var slug: String
  private lateinit var storyName: String
  private var perPage = 10

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

    binding = ActivityDownloadStoriesBinding.inflate(layoutInflater)
    setContentView(binding.root)

    //    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    slug = intent.getStringExtra("slug").toString()
    storyName = intent.getStringExtra("name").toString()

    // Set the Toolbar as ActionBar
    setSupportActionBar(binding.toolbarDownload)
    binding.toolbarDownload.title = "Tải truyện - $storyName"

    //  load data
    loadData(1)

    // Enable the Up button
    supportActionBar?.setDisplayHomeAsUpEnabled(true)

    // Set the icon for the Up button
    supportActionBar?.setHomeAsUpIndicator(R.drawable.baseline_arrow_back_24)
  }

  override fun onOptionsItemSelected(item: MenuItem): Boolean {
    when (item.itemId) {
      android.R.id.home -> {
        // Handle the Up button click here
        finish()
        return true
      }
    }
    return super.onOptionsItemSelected(item)
  }

  @SuppressLint("SetTextI18n", "ResourceAsColor", "InflateParams", "MissingInflatedId")
  private fun loadData(page: Int) {
    val api = RetrofitClient.create()

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.downChapter(slug, page, perPage)

      val chapterDownloadResponse = gson.fromJson<ChapterDownloadResponse>(
        response.string(), object : TypeToken<ChapterDownloadResponse>() {}.type
      )

      withContext(Dispatchers.Main) {
        if (!chapterDownloadResponse.status) {
          Toast.makeText(
            this@DownloadStoriesActivity, chapterDownloadResponse.message, Toast.LENGTH_SHORT
          ).show()
          return@withContext
        }

        val listItem = chapterDownloadResponse.body.data

        // Tạo đối tượng LayoutInflater
        val inflater = LayoutInflater.from(this@DownloadStoriesActivity)

        // Inflate file xml thành đối tượng View
        val itemDownload = inflater.inflate(R.layout.item_download, null)

        itemDownload.findViewById<TextView>(R.id.downloadTextView).text =
          "${chapterDownloadResponse.body.from} - ${chapterDownloadResponse.body.to}"

        val storyLocal = Story.getStoryBySlug(this@DownloadStoriesActivity, slug)
        val chapterLocal =
          storyLocal?.id?.let {
            ChapterLocal.getChapterByNumber(
              this@DownloadStoriesActivity,
              it,
              chapterDownloadResponse.body.from
            )
          }
        if (chapterLocal !== null) {
          itemDownload.findViewById<ImageView>(R.id.check_download).visibility = View.VISIBLE
        }

        itemDownload.setOnClickListener {
          downLoadChapter(listItem)
        }

        binding.listItemDownload.addView(itemDownload)
        if (chapterDownloadResponse.body.currentPage < chapterDownloadResponse.body.lastPage) {
          loadData(chapterDownloadResponse.body.currentPage + 1)
        }
      }
    }
  }

  private fun downLoadChapter(listItem: ArrayList<Chapter>) = runBlocking {
    val getIdOrCreateStory = async { getIdOrCreateStory() }
    val storyId = getIdOrCreateStory.await()
    val sizeItem = listItem.size
    var checkUpdate = false

    if (storyId == 0L) return@runBlocking

    for (i in 0 until sizeItem) {
      val chapter = listItem[i]
      val chapterLocal = ChapterLocal.getChapterByNumber(
        this@DownloadStoriesActivity,
        storyId,
        chapter.number
      )
      if (chapterLocal !== null) {
        chapterLocal.update(this@DownloadStoriesActivity)
        checkUpdate = true
        continue
      }
      val newChapterLocal =
        ChapterLocal(null, chapter.number, chapter.name, chapter.content, storyId)
      newChapterLocal.insert(this@DownloadStoriesActivity)
    }

    if (checkUpdate) {
      Toast.makeText(
        this@DownloadStoriesActivity,
        "Đã cập nhật thành công",
        Toast.LENGTH_SHORT
      ).show()
      return@runBlocking
    }

    Toast.makeText(
      this@DownloadStoriesActivity,
      "Đã tải thành công",
      Toast.LENGTH_SHORT
    ).show()

  }

  private suspend fun getIdOrCreateStory(): Long {
    val storyBySlug = Story.getStoryBySlug(this, slug)
    if (storyBySlug !== null && storyBySlug.id !== null) {
      return storyBySlug.id!!
    }

    val api = RetrofitClient.create()
    val response = api.showStory(slug)

    val showStoryResponse = gson.fromJson<ShowStoryResponse>(
      response.string(), object : TypeToken<ShowStoryResponse>() {}.type
    )

    val imageUrl = Util.saveImageFromUrl(
      this@DownloadStoriesActivity, showStoryResponse.body.image, slug
    )

    if (imageUrl == null) {
      Toast.makeText(
        this@DownloadStoriesActivity,
        "Lỗi lưu ảnh", Toast.LENGTH_SHORT
      ).show()
      return 0L
    }
    val story = Story(null, showStoryResponse.body.name, imageUrl, slug)
    return story.insert(this@DownloadStoriesActivity)
  }
}