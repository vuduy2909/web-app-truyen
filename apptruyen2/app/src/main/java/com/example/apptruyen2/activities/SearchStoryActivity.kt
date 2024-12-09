package com.example.apptruyen2.activities

import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.R
import com.example.apptruyen2.constant.TypeStoryEnum
import com.example.apptruyen2.databinding.ActivitySearchStoryBinding
import com.example.apptruyen2.fragment.BottomNavFragment
import com.example.apptruyen2.helper.LoadListDataHelper
import com.example.apptruyen2.helper.Util

class SearchStoryActivity : AppCompatActivity(), TextWatcher {
  private lateinit var binding: ActivitySearchStoryBinding
  private lateinit var recyclerView: RecyclerView
  private lateinit var loadListDataHelper: LoadListDataHelper
  private var query = ""

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
    binding = ActivitySearchStoryBinding.inflate(layoutInflater)
    setContentView(binding.root)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

//    set recycleView
    recyclerView = binding.recyclerView

    loadListDataHelper = LoadListDataHelper(this, TypeStoryEnum.SEARCH)
    loadListDataHelper.handleRecycleView(recyclerView)

    // Lắng nghe sự kiện cuộn của RecyclerView
    binding.swipeRefreshLayout.setOnRefreshListener {
      // Thực hiện các thao tác để load lại dữ liệu
      // Sau khi hoàn thành, gọi phương thức setRefreshing(false) để ẩn hiệu ứng làm mới
      loadListDataHelper.loadData(1, query)
      binding.swipeRefreshLayout.isRefreshing = false
    }

    binding.searchInp.addTextChangedListener(this)

    supportFragmentManager.beginTransaction()
      .replace(R.id.bottomNav, BottomNavFragment())
      .commit()
  }

  override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {

  }

  override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {

  }

  override fun afterTextChanged(s: Editable?) {
    query = s.toString()
    loadListDataHelper.loadData(1, query)
  }
}