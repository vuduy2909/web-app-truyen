package com.example.apptruyen2.fragment

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.text.HtmlCompat
import androidx.fragment.app.Fragment
import com.example.apptruyen2.databinding.FragmentIntroStoryBinding

class IntroStoryFragment : Fragment() {
  private lateinit var binding: FragmentIntroStoryBinding

  override fun onCreateView(
    inflater: LayoutInflater,
    container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View? {
    binding = FragmentIntroStoryBinding.inflate(inflater, container, false)
    val view = binding.root
    // Lấy Bundle đã được truyền từ Activity
    val bundle = arguments
    val description = bundle?.getString("description")
    // Hiển thị dữ liệu trong Fragment
    binding.tvIntroStory.text =
      description?.let {
        HtmlCompat.fromHtml(it, HtmlCompat.FROM_HTML_SEPARATOR_LINE_BREAK_PARAGRAPH)
      }

    return view
  }

}