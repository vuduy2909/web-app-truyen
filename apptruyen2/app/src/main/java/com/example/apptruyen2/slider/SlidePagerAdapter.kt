package com.example.apptruyen2.slider

import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentManager
import androidx.fragment.app.FragmentPagerAdapter

@Suppress("DEPRECATION")
class SlidePagerAdapter(fm: FragmentManager) : FragmentPagerAdapter(fm) {

  private val fragments = ArrayList<SlideFragment>()

  fun addSlide(slideFragment: SlideFragment) {
    fragments.add(slideFragment)
    notifyDataSetChanged()
  }

  override fun getCount(): Int {
    return fragments.size
  }

  override fun getItem(position: Int): Fragment {
    return fragments[position]
  }
}
