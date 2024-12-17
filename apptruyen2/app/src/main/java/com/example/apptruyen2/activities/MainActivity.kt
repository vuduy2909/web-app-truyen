package com.example.apptruyen2.activities

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.example.apptruyen2.R
import com.example.apptruyen2.databinding.ActivityMainBinding
import com.example.apptruyen2.fragment.*
import com.example.apptruyen2.helper.Util
import com.example.apptruyen2.slider.SlideContainerFragment


class MainActivity : AppCompatActivity() {
  private lateinit var binding: ActivityMainBinding //ten class sẽ dc sinh ra từ file layout

  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

    //    kiểm tra xem có bật mạng không nè
    if (!Util.checkNetworkConnected(this)) return

    //khởi tạo Viewbinding
    binding = ActivityMainBinding.inflate(layoutInflater)
    setContentView(binding.root)

    supportFragmentManager.beginTransaction()
      // Thay thế nội dung của container bằng 1 fragment mới
      .replace(R.id.your_placeholder1, ListStoryFragment())
      // Hoàn thành các thay đổi được thêm ở trên
      .commit()
    binding.imgBtnNew.setOnClickListener {
      supportFragmentManager.beginTransaction()
        .replace(R.id.your_placeholder1, ListStoryFragment())
        .commit()
    }
    binding.imgBtnFull.setOnClickListener {
      supportFragmentManager.beginTransaction()
        .replace(R.id.your_placeholder1, ListFullFragment())
        .commit()
    }
    binding.imgBtnTopStar.setOnClickListener {
      supportFragmentManager.beginTransaction()
        .replace(R.id.your_placeholder1, ListTopStarsFragment())
        .commit()
    }
    binding.imgBtnTopView.setOnClickListener {
      supportFragmentManager.beginTransaction()
        .replace(R.id.your_placeholder1, ListTopViewsFragment())
        .commit()
    }

    val slideContainerFragment = SlideContainerFragment(supportFragmentManager, 2000)
    supportFragmentManager.beginTransaction().add(R.id.imageSlider, slideContainerFragment).commit()

    supportFragmentManager.beginTransaction()
      // Thay thế nội dung của container bằng 1 fragment mới
      .replace(R.id.bottomNav, BottomNavFragment())
      // Hoàn thành các thay đổi được thêm ở trên
      .commit()
  }

}