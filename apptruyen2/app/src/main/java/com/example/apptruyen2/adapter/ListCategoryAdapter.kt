package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.data.listcategory.CategoryItem
import com.example.apptruyen2.databinding.ItemListCategoryBinding
import com.example.apptruyen2.view.OnItemCategoryClickListener

class ListCategoryAdapter(val context: Context, val onItemCategoryClick: OnItemCategoryClickListener) : RecyclerView.Adapter<ListCategoryAdapter.ViewHolder>()  {

    private var listCategories: ArrayList<CategoryItem> = arrayListOf()

    // Phương thức để gán dữ liệu mới nhất vào adapter, thay thế cho dữ liệu cũ
    @SuppressLint("NotifyDataSetChanged")
    fun setCategories(newCategories: ArrayList<CategoryItem>) {
        listCategories.clear()
        listCategories = newCategories
        notifyDataSetChanged()
    }

    inner class ViewHolder(private val binding: ItemListCategoryBinding) : RecyclerView.ViewHolder(binding.root){
        @SuppressLint("SetTextI18n")
        fun bind(listCategories: CategoryItem) {
            binding.tvNameCategory.text = listCategories.name
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {

        val binding = ItemListCategoryBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val listCategories = listCategories[position]
        holder.bind(listCategories)
        //lắng nghe item click chọn
        holder.itemView.setOnClickListener {
            onItemCategoryClick.onItemCategoryClick(listCategories)
        }
    }
    override fun getItemCount() = listCategories.size
}