package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import android.widget.ImageButton
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.example.apptruyen2.R
import com.example.apptruyen2.data.history.History
import com.example.apptruyen2.databinding.ItemHistoryBinding
import com.example.apptruyen2.view.OnItemHistoryClickListener

class ListHistoryAdapter(val context: Context, val onItemClick: OnItemHistoryClickListener) :
  RecyclerView.Adapter<ListHistoryAdapter.ViewHolder>() {

  private var histories: ArrayList<History> = arrayListOf()

  // Phương thức để thêm dữ liệu mới vào cuối danh sách
  @SuppressLint("NotifyDataSetChanged")
  fun allHistory(newStory: ArrayList<History>) {
    histories = newStory
    notifyDataSetChanged()
  }

  class ViewHolder(private val binding: ItemHistoryBinding) :
    RecyclerView.ViewHolder(binding.root) {

    @SuppressLint("SetTextI18n")
    fun bind(history: History, context: Context) {
      Glide.with(context).load(history.image).into(binding.imageStory)
      binding.tvName.text = history.name
      binding.tvHistoryChapter.text = "Xem tiếp chương: " + history.chapterNumber
    }
  }

  override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
    val binding = ItemHistoryBinding.inflate(LayoutInflater.from(parent.context), parent, false)
    return ViewHolder(binding)
  }

  override fun onBindViewHolder(holder: ViewHolder, position: Int) {
    val history = histories[position]
    holder.bind(history, context)

//        //lắng nghe item click chọn
    holder.itemView.setOnClickListener {
      onItemClick.onItemHistoryClick(history)
    }

    val deleteBtn = holder.itemView.findViewById<ImageButton>(R.id.imgBtnDelete)
    deleteBtn.setOnClickListener { onItemClick.onBtnDeleteItemHistoryClick(history.slug) }
  }

  override fun getItemCount() = histories.size
}