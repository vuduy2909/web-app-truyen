package com.example.apptruyen2.activities

import android.os.Bundle
import android.view.MenuItem
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.text.HtmlCompat
import com.example.apptruyen2.R
import com.example.apptruyen2.databinding.ActivityShowLocalChapterBinding
import com.example.apptruyen2.model.Chapter

class ShowLocalChapterActivity : AppCompatActivity() {
  private lateinit var binding: ActivityShowLocalChapterBinding
  private var storyId: Long = 0

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

    binding = ActivityShowLocalChapterBinding.inflate(layoutInflater)
    setContentView(binding.root)

    storyId = intent.getLongExtra("story_id", 0)
    val number: Int = intent.getIntExtra("number", 1)
    if (storyId == 0L) {
      finish()
      return
    }
    loadData(number)

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

  private fun loadData(number: Int = 1) {
    val data = Chapter.getChapterByNumber(this, storyId, number) ?: return
    binding.toolbar3.title = "Chương ${data.number}. ${data.name}"

    binding.tvContent.text =
      data.content?.let { HtmlCompat.fromHtml(it, HtmlCompat.FROM_HTML_MODE_LEGACY) }

    linkPointerHandle(number)
  }

  private fun linkPointerHandle(number: Int) {
    binding.bottomNavigationView.setOnItemSelectedListener {
      val previousChapter = Chapter.getChapterByNumber(this, storyId, number - 1)
      val nextChapter = Chapter.getChapterByNumber(this, storyId, number + 1)

      when (it.itemId) {
        R.id.previous -> {

          if (previousChapter != null) {
            loadData(number - 1)
            return@setOnItemSelectedListener true
          }

          Toast.makeText(
            this@ShowLocalChapterActivity,
            "Bạn đang ở chương đầu tiên.",
            Toast.LENGTH_SHORT
          ).show()
          true
        }

        R.id.next -> {
          if (nextChapter != null) {
            loadData(number + 1)
            return@setOnItemSelectedListener true
          }

          Toast.makeText(
            this@ShowLocalChapterActivity,
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