package com.example.apptruyen2.activities

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.GridLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.R
import com.example.apptruyen2.adapter.ListCategoryAdapter
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.listcategory.CategoriesResponse
import com.example.apptruyen2.data.listcategory.CategoryItem
import com.example.apptruyen2.databinding.ActivityListCategoryBinding
import com.example.apptruyen2.fragment.BottomNavFragment
import com.example.apptruyen2.helper.Util
import com.example.apptruyen2.view.OnItemCategoryClickListener
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class ListCategoryActivity : AppCompatActivity() {
    private lateinit var binding: ActivityListCategoryBinding
    private val gson = Gson()
    private lateinit var adapter: ListCategoryAdapter
    private lateinit var recyclerView: RecyclerView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

//    kiểm tra xem có bật mạng không nè
        if (!Util.checkNetworkConnected(this)) return

        binding = ActivityListCategoryBinding.inflate(layoutInflater)
        setContentView(binding.root)

        recyclerView = binding.recyclerView
        recyclerView.layoutManager = GridLayoutManager(applicationContext, 2, GridLayoutManager.VERTICAL, false)
        adapter = ListCategoryAdapter(this, object: OnItemCategoryClickListener {

            override fun onItemCategoryClick(listCategories: CategoryItem) {
                val intent = Intent(this@ListCategoryActivity, ListStoryByCategoryActivity::class.java)
                intent.putExtra("slug", listCategories.slug)
                intent.putExtra("name", listCategories.name)
                startActivity(intent)
            }

        })
        recyclerView.adapter = adapter
        loadListCategories()

        supportFragmentManager.beginTransaction()
            // Thay thế nội dung của container bằng 1 fragment mới
            .replace(R.id.bottomNav, BottomNavFragment())
            // Hoàn thành các thay đổi được thêm ở trên
            .commit()
    }

    private fun loadListCategories() {
        val api = RetrofitClient.create()

        CoroutineScope(Dispatchers.IO).launch {
            val response = api.listCategories()
            val categoriesResponse = gson.fromJson<CategoriesResponse>(
                response.string(),
                object : TypeToken<CategoriesResponse>() {}.type
            )

            withContext(Dispatchers.Main) {
                categoriesResponse.body.let { adapter.setCategories(it) }
            }
        }
    }
}