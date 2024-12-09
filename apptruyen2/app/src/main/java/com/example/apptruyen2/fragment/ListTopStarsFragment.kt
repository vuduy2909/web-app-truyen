package com.example.apptruyen2.fragment

import android.annotation.SuppressLint
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.constant.TypeStoryEnum
import com.example.apptruyen2.databinding.FragmentListStoryBinding
import com.example.apptruyen2.helper.LoadListDataHelper

@SuppressLint("StaticFieldLeak")
private lateinit var binding: FragmentListStoryBinding

class ListTopStarsFragment : Fragment() {
  private lateinit var recyclerView: RecyclerView
  private lateinit var loadListDataHelper: LoadListDataHelper

  override fun onCreateView(
    inflater: LayoutInflater,
    container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View? {
    binding = FragmentListStoryBinding.inflate(inflater, container, false)
    val view = binding.root

    binding.titleList.text = "XẾP HẠNG ĐÁNH GIÁ"
    recyclerView = binding.recyclerView

    loadListDataHelper = LoadListDataHelper(requireContext(), TypeStoryEnum.TOP_STARS)
    loadListDataHelper.handleRecycleView(recyclerView)

    binding.swipeRefreshLayout.setOnRefreshListener {
      loadListDataHelper.loadData(1)
      binding.swipeRefreshLayout.isRefreshing = false
    }

    return view
  }
}