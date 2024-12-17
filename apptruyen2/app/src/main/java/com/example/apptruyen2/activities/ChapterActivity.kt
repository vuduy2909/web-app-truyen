package com.example.apptruyen2.activities

import android.os.Bundle
import android.view.MenuItem
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.text.HtmlCompat
import com.example.apptruyen2.R
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.showchapter.LinkPointer
import com.example.apptruyen2.data.showchapter.ShowChapterResponse
import com.example.apptruyen2.databinding.ActivityChapterBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class ChapterActivity : AppCompatActivity() {
  private lateinit var binding: ActivityChapterBinding
  private val gson = Gson()

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    binding = ActivityChapterBinding.inflate(layoutInflater)
    setContentView(binding.root)

    if (intent == null) {
      finish()
      return
    }

    val slug = intent.getStringExtra("slug")
    val number = intent.getIntExtra("number", 1)

    if (slug === null) {
      Toast.makeText(this, "Thiếu slug truyện", Toast.LENGTH_SHORT).show()
      finish()
      return
    }

    loadShowChapter(slug, number)

    // Set the Toolbar as ActionBar
    setSupportActionBar(binding.toolbar3)

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

  private fun loadShowChapter(slug: String, number: Int) {
    val user = SharedPreferencesHelper(this).getUser()
    val api = RetrofitClient.create(user?.token)

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.showChapter(slug, number)

      val showChapterResponse = gson.fromJson<ShowChapterResponse>(
        response.string(),
        object : TypeToken<ShowChapterResponse>() {}.type
      )

      withContext(Dispatchers.Main) {
        val contentChapter = showChapterResponse.body?.data
        binding.toolbar3.title =
          "Chương ${contentChapter?.number}. ${contentChapter?.chapterName}"

        binding.tvContent.text =
          contentChapter?.content?.let { HtmlCompat.fromHtml(it, HtmlCompat.FROM_HTML_MODE_LEGACY) }
      }
      showChapterResponse.body?.links?.let { linkPointerHandle(slug, it) }
    }
  }

  private fun linkPointerHandle(slug: String, linkPointer: LinkPointer) {
    val previousChapter = linkPointer.previous?.number
    val nextChapter = linkPointer.next?.number

    binding.bottomNavigationView.setOnItemSelectedListener {
      when (it.itemId) {
        R.id.previous -> {
          if (previousChapter != null) {
            loadShowChapter(slug, previousChapter)
            return@setOnItemSelectedListener true
          }

          Toast.makeText(
            this@ChapterActivity,
            "Bạn đang ở chương đầu tiên.",
            Toast.LENGTH_SHORT
          ).show()
          true
        }
        R.id.next -> {
          if (nextChapter != null) {
            loadShowChapter(slug, nextChapter)
            return@setOnItemSelectedListener true
          }

          Toast.makeText(
            this@ChapterActivity,
            "Bạn đang ở chương cuối.",
            Toast.LENGTH_SHORT
          ).show()

          true
        }
        else -> false
      }
    }
  }
}