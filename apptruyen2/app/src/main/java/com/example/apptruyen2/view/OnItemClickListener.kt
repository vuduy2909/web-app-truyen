package com.example.apptruyen2.view

import com.example.apptruyen2.data.liststory.Story

//Lắng nghe sự kiện click vào một item của RecyclerView
interface OnItemClickListener {
    fun onItemClick(story: Story)
}