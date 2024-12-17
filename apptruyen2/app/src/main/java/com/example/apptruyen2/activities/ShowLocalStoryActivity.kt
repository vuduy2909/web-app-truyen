package com.example.apptruyen2.activities

import android.os.Bundle
import android.view.MenuItem
import android.view.View
import android.widget.AdapterView
import android.widget.ArrayAdapter
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.example.apptruyen2.R
import com.example.apptruyen2.adapter.LocalChapterAdapter
import com.example.apptruyen2.databinding.ActivityShowLocalStoryBinding
import com.example.apptruyen2.model.Chapter
import com.example.apptruyen2.model.Story
import com.google.gson.Gson

class ShowLocalStoryActivity : AppCompatActivity() {
  private lateinit var binding: ActivityShowLocalStoryBinding

  private val gson = Gson()
  private lateinit var story: Story
  private lateinit var adapter: LocalChapterAdapter
  private val options = arrayOf("cũ nhất", "mới nhất")
  private var sort = "asc"

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
    binding = ActivityShowLocalStoryBinding.inflate(layoutInflater)
    setContentView(binding.root)

    val storyJson = intent.getStringExtra("story")

    if (storyJson == null) {
      finish()
      return
    }
    adapter = LocalChapterAdapter(this)
    binding.recyclerView.adapter = adapter
    binding.recyclerView.layoutManager = LinearLayoutManager(this)

    story = gson.fromJson(storyJson, Story::class.java)
    loadData()

    binding.toolbar.title = story.name
    // Set the Toolbar as ActionBar
    setSupportActionBar(binding.toolbar)

    // Enable the Up button
    supportActionBar?.setDisplayHomeAsUpEnabled(true)

    // Set the icon for the Up button
    supportActionBar?.setHomeAsUpIndicator(R.drawable.baseline_arrow_back_24)


    //  Set cho spiner
    binding.spinner.adapter =
      ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, options)
    binding.spinner.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {
      override fun onItemSelected(parent: AdapterView<*>?, view: View?, position: Int, id: Long) {
        if (position == 0) {
          sort = "asc"
          loadData()
          return
        }
        sort = "desc"
        loadData()
      }

      override fun onNothingSelected(parent: AdapterView<*>?) {
        // do nothing
      }
    }
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

  private fun loadData() {
    if (story.id == null) return
    val data = Chapter.all(this, story.id!!, sort)
    adapter.setChapters(data)
  }
}