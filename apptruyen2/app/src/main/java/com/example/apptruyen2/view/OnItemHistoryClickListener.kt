package com.example.apptruyen2.view

import com.example.apptruyen2.data.history.History

//Lắng nghe sự kiện click vào một item của RecyclerView
interface OnItemHistoryClickListener {
    fun onItemHistoryClick(history: History)

    fun onBtnDeleteItemHistoryClick(slug: String)
}