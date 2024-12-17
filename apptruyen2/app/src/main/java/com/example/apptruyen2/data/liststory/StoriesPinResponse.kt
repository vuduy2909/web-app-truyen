package com.example.apptruyen2.data.liststory

data class StoriesPinResponse(
  val status: Boolean,
  val message: String,
  val body: ArrayList<Story>?
)
