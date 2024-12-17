package com.example.apptruyen2.activities

import android.annotation.SuppressLint
import android.app.Dialog
import android.content.Intent
import android.os.Bundle
import android.view.MenuItem
import android.widget.Button
import android.widget.RatingBar
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.bumptech.glide.Glide
import com.example.apptruyen2.R
import com.example.apptruyen2.adapter.ViewPagerAdapter
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.history.ShowHistoryResponse
import com.example.apptruyen2.data.show_star.CreateStarsResponse
import com.example.apptruyen2.data.show_star.ShowStarResponse
import com.example.apptruyen2.data.showstory.ShowStoryResponse
import com.example.apptruyen2.databinding.ActivityInfoStoryBinding
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.google.android.material.tabs.TabLayoutMediator
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class InfoStoryActivity : AppCompatActivity() {
  private lateinit var binding: ActivityInfoStoryBinding

  private val gson = Gson()

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    binding = ActivityInfoStoryBinding.inflate(layoutInflater)
    setContentView(binding.root)

    val slug = intent.getStringExtra("slug")
    if (slug != null) {
      loadShowStory(slug)
      intent.putExtra("slug", slug)
    }

    if (slug != null) {
      showStarsByStory(slug)
    }

    // Set the Toolbar as ActionBar
    setSupportActionBar(binding.toolbar2)

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

  private fun showRatingBar(slug: String) {
    val dialog = Dialog(this)
    dialog.setContentView(R.layout.rating_dialog)

    val ratingBar = dialog.findViewById<RatingBar>(R.id.ratingBar)
    val buttonSubmit = dialog.findViewById<Button>(R.id.buttonSubmit)
    val buttonCancel = dialog.findViewById<Button>(R.id.buttonCancel)

    buttonSubmit.setOnClickListener {
      val rating = ratingBar.rating
      // Cập nhật giá trị đánh giá vào cơ sở dữ liệu hoặc thực hiện các xử lý khác nếu cần thiết
      submitRating(slug, rating.toInt())
      dialog.dismiss() // Đóng Dialog
    }

    buttonCancel.setOnClickListener {
      dialog.dismiss()
    }
    dialog.show()
  }

  private fun evenBottomNavHandle(slug: String, name: String) {
    binding.bottomNavigationView.setOnItemSelectedListener {
      when (it.itemId) {
        R.id.read -> {
          readChapterStory(slug)
          true
        }
        R.id.rank_btn -> {
          showRatingBar(slug)
          true
        }
        R.id.comment_btn -> {
          val intent = Intent(this, CommentActivity::class.java)
          intent.putExtra("slug", slug)
          intent.putExtra("name", name)
          startActivity(intent)
          true
        }
        R.id.download_btn -> {
          val intent = Intent(this, DownloadStoriesActivity::class.java)
          intent.putExtra("slug", slug)
          intent.putExtra("name", name)
          startActivity(intent)
          true
        }

        else -> false
      }
    }
  }

  private fun submitRating(slug: String, ratingNumber: Int) {
    val user = SharedPreferencesHelper(this).getUser()
    val api = RetrofitClient.create(user?.token)

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.createStar(slug, ratingNumber)

      val createStarsResponse = gson.fromJson<CreateStarsResponse>(
        response.string(),
        object : TypeToken<CreateStarsResponse>() {}.type
      )
      withContext(Dispatchers.Main) {
        if (createStarsResponse.status) {
          showStarsByStory(slug)
        }
        Toast
          .makeText(
            this@InfoStoryActivity,
            createStarsResponse.message,
            Toast.LENGTH_SHORT
          )
          .show()
      }
    }
  }

  private fun loadShowStory(slug: String) {
    val api = RetrofitClient.create()

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.showStory(slug)

      val showStoryResponse = gson.fromJson<ShowStoryResponse>(
        response.string(),
        object : TypeToken<ShowStoryResponse>() {}.type
      )

      withContext(Dispatchers.Main) {
        val infoStory = showStoryResponse.body
        Glide.with(this@InfoStoryActivity).load(infoStory.image).into(binding.imgStory)
        binding.tvNameStory.text = infoStory.name
        binding.tvFull.text = if (infoStory.chapter.isFull) "[FULL]" else ""
        binding.tvAuthor.text = infoStory.author.name
        binding.tvChapterCount.text = infoStory.chapter.count.toString()
        binding.tvViewCount.text = infoStory.viewCount.toString()

        val categoriesStory = infoStory.categoriesStory
        for (category in categoriesStory) {
          val categories = categoriesStory.map { it.name }
          binding.tvCategories.text = categories.joinToString(", ")
        }

        val mBundle = Bundle()
        mBundle.putString("description", infoStory.descriptions)
        mBundle.putString("slug", slug)

        val adapter = ViewPagerAdapter(supportFragmentManager, lifecycle, mBundle)

        binding.viewPager2.adapter = adapter

        TabLayoutMediator(binding.tabLayout, binding.viewPager2) { tab, position ->
          when (position) {
            0 -> {
              tab.text = "Giới thiệu"
            }
            1 -> {
              tab.text = "Chapter"
            }
          }
        }.attach()


        //    Set click cho bottom nav
        evenBottomNavHandle(slug, infoStory.name)
      }
    }
  }

  @SuppressLint("SetTextI18n")
  private fun showStarsByStory(slug: String) {
    val api = RetrofitClient.create()

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.showStarsByStory(slug)

      val showStarsResponse = gson.fromJson(
        response.string(),
        ShowStarResponse::class.java
      )
      if (!showStarsResponse.status) return@launch

      withContext(Dispatchers.Main) {
        binding.tvStars.text =
          "${showStarsResponse.body?.starsAvg}/5 sao từ ${showStarsResponse.body?.personCount} người"
      }
    }
  }

  private fun readChapterStory(slug: String) {
    val user = SharedPreferencesHelper(this).getUser()
    if (user === null) {
      showChapter(slug)
      return
    }
    val api = RetrofitClient.create(user.token)

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.showHistory(slug)

      val showHistoryResponse = gson.fromJson<ShowHistoryResponse>(
        response.string(),
        object : TypeToken<ShowHistoryResponse>() {}.type
      )

      if (!showHistoryResponse.status) {
        showChapter(slug)
        return@launch
      }

      showHistoryResponse.body?.chapterNumber?.let { showChapter(slug, it) }
    }
  }

  private fun showChapter(slug: String, number: Int = 1) {
    val intent = Intent(this, ChapterActivity::class.java)
    intent.putExtra("slug", slug)
    intent.putExtra("number", number)
    startActivity(intent)
  }
}