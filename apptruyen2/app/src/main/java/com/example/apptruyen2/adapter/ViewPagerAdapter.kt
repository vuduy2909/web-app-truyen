package com.example.apptruyen2.adapter

import android.os.Bundle
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentManager
import androidx.lifecycle.Lifecycle
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.example.apptruyen2.fragment.IntroStoryFragment
import com.example.apptruyen2.fragment.ListChapterFragment

class ViewPagerAdapter(fragmentManager: FragmentManager, lifecycle: Lifecycle, private val bundle: Bundle) :
    FragmentStateAdapter(fragmentManager, lifecycle) {
    override fun getItemCount(): Int {
        return 2
    }

    override fun createFragment(position: Int): Fragment {
        val introStoryFragment =  IntroStoryFragment()
        val listChapterFragment = ListChapterFragment()


        introStoryFragment.arguments = bundle
        listChapterFragment.arguments = bundle
        return when (position) {
            0 -> {
                introStoryFragment
            }
            1 -> {
                listChapterFragment
            }
            else -> {
                introStoryFragment
            }
        }
    }
}
