package com.example.apptruyen2.data.showchapter

import com.google.gson.annotations.SerializedName

data class LinkPointer(
    val current: LinkItem,
    @SerializedName("pre") val previous: LinkItem?,
    val next: LinkItem?,
    val first: LinkItem,
    val last: LinkItem
)
