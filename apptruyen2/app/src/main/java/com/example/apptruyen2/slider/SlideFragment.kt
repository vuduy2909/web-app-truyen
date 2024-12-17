package com.example.apptruyen2.slider

import android.annotation.SuppressLint
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.bumptech.glide.Glide
import com.example.apptruyen2.activities.InfoStoryActivity
import com.example.apptruyen2.data.liststory.Story
import com.example.apptruyen2.databinding.FragmentSlideBinding

class SlideFragment(private var story: Story) : Fragment() {
  private lateinit var binding: FragmentSlideBinding

  @SuppressLint("MissingInflatedId", "SetTextI18n")
  override fun onCreateView(
    inflater: LayoutInflater,
    container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View {
    binding = FragmentSlideBinding.inflate(inflater, container, false)
    val view = binding.root

    Glide.with(requireContext()).load(story.image).into(binding.imageview)
    binding.title.text = story.name
    binding.chapterCount.text = story.chapter.count.toString() + " Chương"

    binding.root.setOnClickListener {
      val intent = Intent(requireContext(), InfoStoryActivity::class.java)
      intent.putExtra("slug", story.slug)
      startActivity(intent)
    }
    return view
  }
}
