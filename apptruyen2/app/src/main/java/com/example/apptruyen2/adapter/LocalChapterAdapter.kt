package com.example.apptruyen2.adapter

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.example.apptruyen2.activities.ShowLocalChapterActivity
import com.example.apptruyen2.databinding.ItemLocalChapterBinding
import com.example.apptruyen2.model.Chapter

class LocalChapterAdapter(
  val context: Context
) : RecyclerView.Adapter<LocalChapterAdapter.ViewHolder>() {

  private var chapters: ArrayList<Chapter> = arrayListOf()

  @SuppressLint("NotifyDataSetChanged")
  fun setChapters(newChapter: List<Chapter>) {
    chapters.clear()
    chapters.addAll(newChapter)
    notifyDataSetChanged()
  }


  inner class ViewHolder(private val binding: ItemLocalChapterBinding) :
    RecyclerView.ViewHolder(binding.root) {

    @SuppressLint("SetTextI18n")
    fun bind(chapter: Chapter) {
      binding.tvNameChapter.text = chapter.name
      binding.tvNumChapter.text = chapter.number.toString()
    }
  }

  override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
    val binding = ItemLocalChapterBinding.inflate(LayoutInflater.from(parent.context), parent, false)
    return ViewHolder(binding)
  }

  override fun onBindViewHolder(holder: ViewHolder, position: Int) {
    val chapter = chapters[position]
    holder.bind(chapter)

    //lắng nghe item click chọn
    holder.itemView.setOnClickListener {
      val intent = Intent(context, ShowLocalChapterActivity::class.java)
      intent.putExtra("story_id", chapter.storyId)
      intent.putExtra("number", chapter.number)
      context.startActivity(intent)
    }
  }

  override fun getItemCount() = chapters.size

}