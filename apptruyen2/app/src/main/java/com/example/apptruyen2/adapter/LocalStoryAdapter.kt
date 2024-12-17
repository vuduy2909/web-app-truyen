package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.example.apptruyen2.databinding.ItemLocalStoryBinding
import com.example.apptruyen2.view.OnItemLocalStoryClickListener
import com.example.apptruyen2.model.Story as LocalStory

class LocalStoryAdapter(
  val context: Context,
  private val onItemClick: OnItemLocalStoryClickListener
) : RecyclerView.Adapter<LocalStoryAdapter.ViewHolder>() {

  private var story: ArrayList<LocalStory> = arrayListOf()

  @SuppressLint("NotifyDataSetChanged")
  fun setStory(newStory: List<LocalStory>) {
    story.clear()
    story.addAll(newStory)
    notifyDataSetChanged()
  }


  inner class ViewHolder(private val binding: ItemLocalStoryBinding) :
    RecyclerView.ViewHolder(binding.root) {

    @SuppressLint("SetTextI18n")
    fun bind(story: LocalStory, context: Context) {
      Glide.with(context).load(story.image).into(binding.imageStory)
      binding.tvName.text = story.name

      binding.imgBtnDelete.setOnClickListener {

        onItemClick.onDeleteButtonClick(story)
      }
    }
  }

  override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
    val binding = ItemLocalStoryBinding.inflate(LayoutInflater.from(parent.context), parent, false)
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