package com.example.apptruyen2.data.history

data class HistoriesResponse(
  val status: Boolean,
  val message: String,
  val body: ArrayList<History>
)