package com.example.apptruyen2.activities

import android.os.Bundle
import android.view.MenuItem
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.R
import com.example.apptruyen2.constant.TypeStoryEnum
import com.example.apptruyen2.databinding.ActivityListStoryByCategoryBinding
import com.example.apptruyen2.helper.LoadListDataHelper
import com.example.apptruyen2.helper.Util

class ListStoryByCategoryActivity : AppCompatActivity() {
  private lateinit var binding: ActivityListStoryByCategoryBinding
  private lateinit var recyclerView: RecyclerView
  private lateinit var loadListDataHelper: LoadListDataHelper

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
    binding = ActivityListStoryByCategoryBinding.inflate(layoutInflater)
    setContentView(binding.root)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    val slug = intent.getStringExtra("slug")
    val name = intent.getStringExtra("name")
    binding.toolbar4.title = name

    recyclerView = binding.recyclerView
    recyclerView.layoutManager = LinearLayoutManager(this)

    loadListDataHelper = slug?.let { LoadListDataHelper(this, TypeStoryEnum.CATEGORY, it) }!!
    loadListDataHelper.handleRecycleView(recyclerView)

    binding.swipeRefreshLayout.setOnRefreshListener {
      // Thực hiện các thao tác để load lại dữ liệu
      // Sau khi hoàn thành, gọi phương thức setRefreshing(false) để ẩn hiệu ứng làm mới
      loadListDataHelper.loadData(1)
      binding.swipeRefreshLayout.isRefreshing = false
    }

    // Set the Toolbar as ActionBar
    setSupportActionBar(binding.toolbar4)

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

}