package com.example.apptruyen2.helper

import android.content.Context
import com.example.apptruyen2.data.auth.User
import com.google.gson.Gson

class SharedPreferencesHelper(private val context: Context) {

  private val sharedPref = context.getSharedPreferences("user_local", Context.MODE_PRIVATE)
  private val editor = sharedPref.edit()

  fun saveUser(user: User) {
    val userJson = Gson().toJson(user)
    editor.putString("user_json", userJson)
    editor.apply()
  }

  fun getUser(): User? {
    val userJson = sharedPref.getString("user_json", null) ?: return null
    return Gson().fromJson(userJson, User::class.java)
  }

  fun clearUser() {
    editor.clear()
    editor.apply()
  }
}
