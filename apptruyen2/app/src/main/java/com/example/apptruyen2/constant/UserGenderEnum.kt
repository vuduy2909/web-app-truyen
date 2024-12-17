package com.example.apptruyen2.constant

enum class UserGenderEnum(val value: Int) {
  FEMALE(0),
  MALE(1),
  LGBT(2),
  SECRET(3);

  companion object {
    fun fromValue(value: String): UserGenderEnum {
      return when (value) {
        "Nữ" -> FEMALE
        "Nam" -> MALE
        "LGBT" -> LGBT
        else -> SECRET
      }
    }
  }
}