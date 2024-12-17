package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.data.list_chapter.ChapterItem
import com.example.apptruyen2.databinding.ItemChapterBinding
import com.example.apptruyen2.view.OnItemChapterClickListener

class ChapterAdapter(val context: Context, val onItemChapterClick: OnItemChapterClickListener) : RecyclerView.Adapter<ChapterAdapter.ViewHolder>() {

    private var listChapters: ArrayList<ChapterItem> = arrayListOf()

    // Phương thức để gán dữ liệu mới nhất vào adapter, thay thế cho dữ liệu cũ
    @SuppressLint("NotifyDataSetChanged")
    fun setChapters(newChapters: ArrayList<ChapterItem>) {
        listChapters.clear()
        listChapters = newChapters
        notifyDataSetChanged()
    }


    inner class ViewHolder(private val binding: ItemChapterBinding) : RecyclerView.ViewHolder(binding.root) {

        @SuppressLint("SetTextI18n")
        fun bind(listChapters: ChapterItem) {
            binding.tvNameChapter.text = listChapters.name
            binding.tvNumChapter.text = listChapters.number.toString()

        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val binding = ItemChapterBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val listChapters = listChapters[position]
        holder.bind(listChapters)
        //lắng nghe item click chọn
        holder.itemView.setOnClickListener {
            onItemChapterClick.onItemChapterClick(listChapters)
        }
    }

    override fun getItemCount() = listChapters.size
}