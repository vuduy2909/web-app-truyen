package com.example.apptruyen2.helper

import android.content.Context
import android.content.Intent
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.activities.InfoStoryActivity
import com.example.apptruyen2.adapter.StoryAdapter
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.constant.TypeStoryEnum
import com.example.apptruyen2.data.liststory.Story
import com.example.apptruyen2.data.liststory.StoryResponse
import com.example.apptruyen2.data.showcategory.ShowCategoryResponse
import com.example.apptruyen2.view.OnItemClickListener
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import okhttp3.ResponseBody

class LoadListDataHelper(
  val context: Context,
  private val typeStoryEnum: TypeStoryEnum,
  val slug: String = ""
) {
  private val gson = Gson()
  private lateinit var adapter: StoryAdapter
  private var nextPage = 1

  fun handleRecycleView(recyclerView: RecyclerView) {
    //click item trong recyclerView
    adapter = StoryAdapter(context, object : OnItemClickListener {

      override fun onItemClick(story: Story) {
        val intent = Intent(context, InfoStoryActivity::class.java)
        intent.putExtra("slug", story.slug)
        context.startActivity(intent)
      }
    })

    recyclerView.layoutManager = LinearLayoutManager(context)

    recyclerView.adapter = adapter

    var checkLoad = false
    // Lắng nghe sự kiện cuộn của RecyclerView
    recyclerView.addOnScrollListener(object : RecyclerView.OnScrollListener() {
      override fun onScrolled(recyclerView: RecyclerView, dx: Int, dy: Int) {
        super.onScrolled(recyclerView, dx, dy)

        val layoutManager = recyclerView.layoutManager as LinearLayoutManager
        val visibleItemCount = layoutManager.childCount
        val totalItemCount = layoutManager.itemCount
        val firstVisibleItemPosition = layoutManager.findFirstVisibleItemPosition()

        // Kiểm tra xem người dùng đã cuộn đến cuối danh sách hay chưa
        if (visibleItemCount + firstVisibleItemPosition < totalItemCount || firstVisibleItemPosition < 0) {
          checkLoad = false
          return
        }
        if (checkLoad) return

        checkLoad = true
        if (nextPage == -1) {
          Toast
            .makeText(context, "Bạn đang ở cuối danh sách", Toast.LENGTH_SHORT)
            .show()
          return
        }
        loadData(nextPage)
      }
    })

    // Mặc định loadListStories() được gọi khi Fragment được khởi tạo
    loadData(1)
  }

  fun loadData(page: Int, query: String = "") {
    val api = RetrofitClient.create()

    CoroutineScope(Dispatchers.IO).launch {
      val response: ResponseBody
      val data: ArrayList<Story>
      val currentPage: Int
      val lastPage: Int

      when (typeStoryEnum) {
        TypeStoryEnum.NEW -> {
          response = api.listStories(page)
        }
        TypeStoryEnum.FULL -> {
          response = api.listFull(page)
        }
        TypeStoryEnum.TOP_STARS -> {
          response = api.listTopStars(page)
        }
        TypeStoryEnum.TOP_VIEWS -> {
          response = api.listTopViews(page)
        }
        TypeStoryEnum.CATEGORY -> {
          println("slug: $slug")
          response = api.listStoryByCategory(slug, page)
        }
        TypeStoryEnum.SEARCH -> {
          response = api.searchStories(query, page)
        }
        else -> {
          response = api.listStories(page)
        }
      }

      when (typeStoryEnum) {
        TypeStoryEnum.CATEGORY -> {
          val storyResponse = gson.fromJson<ShowCategoryResponse>(
            response.string(),
            object : TypeToken<ShowCategoryResponse>() {}.type
          )
          data = storyResponse.body?.stories?.data ?: ArrayList()
          currentPage = storyResponse.body?.stories?.currentPage ?: 1
          lastPage = storyResponse.body?.stories?.lastPage ?: 1
        }
        else -> {
          val storyResponse = gson.fromJson<StoryResponse>(
            response.string(),
            object : TypeToken<StoryResponse>() {}.type
          )
          data = storyResponse.body?.data ?: ArrayList()
          currentPage = storyResponse.body?.currentPage ?: 1
          lastPage = storyResponse.body?.lastPage ?: 1
        }
      }

      withContext(Dispatchers.Main) {
        if (page == 1) {
          //gán dữ liệu mới nhất vào adapter, thay thế cho dữ liệu cũ.
          adapter.setStory(data)
        } else {
          //thêm dữ liệu mới vào cuối danh sách, giữ nguyên dữ liệu cũ.
          adapter.addStory(data)
        }
      }

      if (currentPage < lastPage) {
        nextPage = currentPage + 1
        return@launch
      }
      nextPage = -1
    }
  }
}