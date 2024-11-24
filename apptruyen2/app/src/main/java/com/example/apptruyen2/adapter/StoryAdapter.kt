package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.example.apptruyen2.data.liststory.Story
import com.example.apptruyen2.databinding.ItemBinding
import com.example.apptruyen2.view.OnItemClickListener

class StoryAdapter(val context: Context, private val onItemClick: OnItemClickListener) : RecyclerView.Adapter<StoryAdapter.ViewHolder>() {

    private var story: ArrayList<Story> = arrayListOf()
    // Phương thức để thêm dữ liệu mới vào cuối danh sách
    @SuppressLint("NotifyDataSetChanged")
    fun addStory(newStory: List<Story>) {
        story.addAll(newStory)
        notifyDataSetChanged()
    }

    // Phương thức để gán dữ liệu mới nhất vào adapter, thay thế cho dữ liệu cũ

    @SuppressLint("NotifyDataSetChanged")
    fun setStory(newStory: List<Story>) {
        story.clear()
        story.addAll(newStory)
        notifyDataSetChanged()
    }


    inner class ViewHolder(private val binding: ItemBinding) : RecyclerView.ViewHolder(binding.root) {

        @SuppressLint("SetTextI18n")
        fun bind(story: Story, context: Context) {
            Glide.with(context).load(story.image).into(binding.imageStory)
            binding.tvName.text = story.name
            binding.tvFull.text = if (story.chapter.isFull) "[FULL]" else ""
            binding.tvUpdatedAt.text = story.updatedAt
            if (story.viewCount != null) {
                binding.tvRank.text = "${story.viewCount} lượt xem"
            }
            if (story.stars != null) {
                binding.tvRank.text = "${story.stars.avg}/5 - ${story.stars.personCount} người"
            }
            binding.tvChapter.text = "${story.chapter.count} chương"
            binding.tvCategories.text = story.categories

        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val binding = ItemBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val story = story[position]
        holder.bind(story, context)

        //lắng nghe item click chọn
        holder.itemView.setOnClickListener {
            onItemClick.onItemClick(story)
        }
    }

    override fun getItemCount() = story.size

}