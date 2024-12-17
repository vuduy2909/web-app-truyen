package com.example.apptruyen2.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.example.apptruyen2.R
import com.example.apptruyen2.adapter.LocalStoryAdapter
import com.example.apptruyen2.databinding.ActivityLocalStoryBinding
import com.example.apptruyen2.fragment.BottomNavFragment
import com.example.apptruyen2.view.OnItemLocalStoryClickListener
import com.google.gson.Gson
import java.io.File
import com.example.apptruyen2.model.Story as LocalStory

class LocalStoryActivity : AppCompatActivity() {
  private lateinit var binding: ActivityLocalStoryBinding

  private lateinit var adapter: LocalStoryAdapter

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
    binding = ActivityLocalStoryBinding.inflate(layoutInflater)
    setContentView(binding.root)

    adapter = LocalStoryAdapter(this, object : OnItemLocalStoryClickListener {
      override fun onItemClick(story: LocalStory) {
        val intent = Intent(this@LocalStoryActivity, ShowLocalStoryActivity::class.java)
        intent.putExtra("story", Gson().toJson(story))
        startActivity(intent)
      }

      override fun onDeleteButtonClick(story: LocalStory) {
        val file = story.image?.let { File(it) }
        if (file?.exists() == true) {
          file.delete()
        }
        story.delete(this@LocalStoryActivity)
        loadData()
        Toast.makeText(this@LocalStoryActivity, "Xóa thành công", Toast.LENGTH_SHORT).show()
      }
    })

    loadData()

    binding.recyclerView.adapter = adapter
    binding.recyclerView.layoutManager = LinearLayoutManager(this)

    supportFragmentManager.beginTransaction()
      // Thay thế nội dung của container bằng 1 fragment mới
      .replace(R.id.bottomNav, BottomNavFragment())
      // Hoàn thành các thay đổi được thêm ở trên
      .commit()
  }

  private fun loadData() {
    val data = LocalStory.all(this)
    adapter.setStory(data)
  }

}