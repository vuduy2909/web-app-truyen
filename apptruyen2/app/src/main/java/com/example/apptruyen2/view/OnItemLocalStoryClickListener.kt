package com.example.apptruyen2.view

import com.example.apptruyen2.model.Story as LocalStory


//Lắng nghe sự kiện click vào một item của RecyclerView
interface OnItemLocalStoryClickListener {
    fun onItemClick(story: LocalStory)
    fun onDeleteButtonClick(story: LocalStory)
}