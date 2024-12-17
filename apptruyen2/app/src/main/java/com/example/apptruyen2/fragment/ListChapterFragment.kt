package com.example.apptruyen2.fragment

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.AdapterView
import android.widget.ArrayAdapter
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.activities.ChapterActivity
import com.example.apptruyen2.adapter.ChapterAdapter
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.list_chapter.ChapterItem
import com.example.apptruyen2.data.list_chapter.ChaptersResponse
import com.example.apptruyen2.databinding.FragmentListChapterBinding
import com.example.apptruyen2.view.OnItemChapterClickListener
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext


class ListChapterFragment : Fragment() {
    private lateinit var binding: FragmentListChapterBinding
    private val options = arrayOf("cũ nhất", "mới nhất")

    private val gson = Gson()
    private lateinit var adapter: ChapterAdapter
    private lateinit var recyclerView: RecyclerView
    private var sort = "asc"

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentListChapterBinding.inflate(inflater, container, false)
        val view = binding.root

// Lấy Bundle đã được truyền từ Activity
        val bundle = arguments
        val slug = bundle?.getString("slug")

        // Hiển thị dữ liệu trong Fragment

        recyclerView = binding.recyclerViewChapter
        recyclerView.layoutManager = LinearLayoutManager(requireContext())
        //click item trong recyclerView
        adapter = ChapterAdapter(requireContext(), object: OnItemChapterClickListener {
            override fun onItemChapterClick(listChapters: ChapterItem) {
                val intent = Intent(requireContext(), ChapterActivity::class.java)
                intent.putExtra("slug", listChapters.slug)
                intent.putExtra("number", listChapters.number)
                startActivity(intent)
            }
        })
        recyclerView.adapter = adapter

        // Mặc định loadListStories() được gọi khi Fragment được khởi tạo
        slug?.let { loadListChapters(it) }

        binding.swipeRefreshLayout.setOnRefreshListener {
            // Thực hiện các thao tác để load lại dữ liệu
            // Sau khi hoàn thành, gọi phương thức setRefreshing(false) để ẩn hiệu ứng làm mới
            slug?.let { loadListChapters(it, sort) }
            binding.swipeRefreshLayout.isRefreshing = false
        }

        binding.spinner.adapter = ArrayAdapter(requireContext(), android.R.layout.simple_spinner_dropdown_item, options)
        binding.spinner.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {
            override fun onItemSelected(parent: AdapterView<*>?, view: View?, position: Int, id: Long) {
                if (slug === null) return
                if (position == 0) {
                    sort = "asc"
                    loadListChapters(slug, sort)
                    return
                }
                sort = "desc"
                loadListChapters(slug, sort)
            }

            override fun onNothingSelected(parent: AdapterView<*>?) {
                // do nothing
            }
        }

        return view
    }

    private fun loadListChapters(slug: String, sort: String? = null) {
        val api = RetrofitClient.create()

        CoroutineScope(Dispatchers.IO).launch {
            val response = api.listChapters(slug, sort)
            val chaptersResponse = gson.fromJson<ChaptersResponse>(
                response.string(),
                object : TypeToken<ChaptersResponse>() {}.type
            )

            withContext(Dispatchers.Main) {
                chaptersResponse.body?.data?.let { adapter.setChapters(it) }
            }
        }
    }

}