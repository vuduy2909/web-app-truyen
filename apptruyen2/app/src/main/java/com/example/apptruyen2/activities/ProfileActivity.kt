package com.example.apptruyen2.activities

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.example.apptruyen2.R
import com.example.apptruyen2.fragment.*
import com.example.apptruyen2.helper.SharedPreferencesHelper
import com.example.apptruyen2.helper.Util
import com.example.apptruyen2.view.OnItemMenuProfileClickListener
import com.google.gson.Gson

class ProfileActivity : AppCompatActivity() {
  private val gson = Gson()

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)
    setContentView(R.layout.activity_profile)

//    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    loadFragmentContent()

    supportFragmentManager.beginTransaction()
      .replace(R.id.bottomNav, BottomNavFragment())
      .commit()
  }

  private fun loadFragmentContent() {
    val user = SharedPreferencesHelper(this).getUser()
    if (user === null) {
      supportFragmentManager.beginTransaction()
        .replace(R.id.content, NoProfileFragment())
        .commit()
      return
    }

    supportFragmentManager.beginTransaction()
      .replace(R.id.content, ProfileFragment(object : OnItemMenuProfileClickListener {
        override fun onItemEditClick() {
          showEditForm()
        }

        override fun onItemChangePasswordClick() {
          showChangePassForm()
        }

      }))
      .commit()
  }

  private fun showChangePassForm() {
    supportFragmentManager.beginTransaction().replace(R.id.content, ChangePasswordFragment())
      .commit()
  }

  private fun showEditForm() {
    supportFragmentManager.beginTransaction().replace(R.id.content, EditProfileFragment()).commit()
  }
}