package com.example.apptruyen2.slider

import android.graphics.Color
import android.os.Bundle
import android.os.Handler
import android.util.TypedValue
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.LinearLayout
import android.widget.TextView
import androidx.fragment.app.Fragment
import androidx.fragment.app.FragmentManager
import androidx.viewpager.widget.ViewPager
import com.example.apptruyen2.R
import com.example.apptruyen2.api.RetrofitClient
import com.example.apptruyen2.data.liststory.StoriesPinResponse
import com.example.apptruyen2.data.liststory.Story
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class SlideContainerFragment(
  private val fragmentManager: FragmentManager,
  private val SLIDE_DELAY: Long = 3000,
) : Fragment() {

  private val gson = Gson()
  private lateinit var handler: Handler // 3 seconds
  private lateinit var slideDots: LinearLayout
  private lateinit var viewPager: ViewPager
  private lateinit var slideAdapter: SlidePagerAdapter
  private lateinit var runnable: Runnable
  private val listSlide: ArrayList<Story> = arrayListOf()
  override fun onCreateView(
    inflater: LayoutInflater, container: ViewGroup?,
    savedInstanceState: Bundle?
  ): View? {
    // Inflate the layout for this fragment
    val rootView = inflater.inflate(R.layout.fragment_slide_container, container, false)

    slideDots = rootView.findViewById(R.id.slide_dots)
    viewPager = rootView.findViewById(R.id.viewPager)

    slideAdapter = SlidePagerAdapter(fragmentManager)
    viewPager.adapter = slideAdapter

    loadStories()

//    Tạo một đối tượng handler xong gán cho cái biến này
    @Suppress("DEPRECATION")
    handler = Handler()

//    tạo đối tượng runnable implement cái Runnable xong gán cho cái runnable luôn
    runnable = object : Runnable {
      override fun run() {
        val currentItem = viewPager.currentItem
        val nextItem = if (currentItem == slideAdapter.count - 1) 0 else currentItem + 1
        viewPager.setCurrentItem(nextItem, true)
        handler.postDelayed(this, SLIDE_DELAY)
      }
    }
    handler.postDelayed(runnable, SLIDE_DELAY)

    eventHandler()

    return rootView
  }

  private fun loadStories() {
    val api = RetrofitClient.create()

    CoroutineScope(Dispatchers.IO).launch {
      val response = api.listStoriesPin()

      val storyResponse = gson.fromJson<StoriesPinResponse>(
        response.string(),
        object : TypeToken<StoriesPinResponse>() {}.type
      )

      withContext(Dispatchers.Main) {
        storyResponse.body?.let {
          for (i in 0 until it.size) {
            val slide = SlideFragment(it[i])
            slideAdapter.addSlide(slide)
          }

          listSlide.addAll(storyResponse.body)

          //    Tạo dots cho slide
          createDots()
        }
      }
    }
  }

  private fun createDots() {
    for (i in 0 until slideAdapter.count) {
      val textView = TextView(context)
      textView.text = "•"
      textView.setTextSize(TypedValue.COMPLEX_UNIT_SP, 20f)
      textView.setTextColor(Color.parseColor("#CCCCCC"))
      textView.alpha = 0.5f
      if (i == 0) {
        textView.alpha = 1f
      }
      slideDots.addView(textView)
    }
  }

  fun activeDot(position: Int) {
    for (i in 0 until slideDots.childCount) {
      val dot = slideDots.getChildAt(i) as TextView
      dot.alpha = 0.5f
    }
    val dot = slideDots.getChildAt(position) as TextView
    dot.alpha = 1f
  }

  private fun eventHandler() {
    viewPager.addOnPageChangeListener(object : ViewPager.OnPageChangeListener {
      override fun onPageScrollStateChanged(state: Int) {
        when (state) {
          ViewPager.SCROLL_STATE_DRAGGING -> {
            handler.removeCallbacksAndMessages(null)
          }
          ViewPager.SCROLL_STATE_IDLE -> {
            handler.removeCallbacksAndMessages(null)
            handler.postDelayed(runnable, SLIDE_DELAY)
          }
        }
      }

      override fun onPageScrolled(
        position: Int,
        positionOffset: Float,
        positionOffsetPixels: Int
      ) {

      }

      override fun onPageSelected(position: Int) {
        activeDot(position)
      }
    })
  }

}