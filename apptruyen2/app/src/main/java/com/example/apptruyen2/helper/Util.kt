package com.example.apptruyen2.helper

import android.content.Context
import android.content.Intent
import android.net.ConnectivityManager
import android.net.NetworkCapabilities
import android.widget.Toast
import com.example.apptruyen2.activities.LocalStoryActivity
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import java.io.File
import java.io.FileOutputStream
import java.net.URL
import java.time.Duration
import java.time.LocalDateTime

class Util {
  companion object {
    fun dpToPx(context: Context, dp: Int): Int {
      val density = context.resources.displayMetrics.density
      return (dp * density).toInt()
    }

    fun getDurationString(startDateTime: LocalDateTime, endDateTime: LocalDateTime): String {
      val duration = Duration.between(startDateTime, endDateTime)
      return when {
        duration.toMinutes() < 1 -> "vừa xong"
        duration.toMinutes() < 60 -> "${duration.toMinutes()} phút"
        duration.toHours() < 24 -> "${duration.toHours()} giờ"
        duration.toDays() < 30 -> "${duration.toDays()} ngày"
        duration.toDays() < 365 -> "${duration.toDays() / 30} tháng"
        else -> "${duration.toDays() / 365} năm"
      }
    }

    fun checkNetworkConnected(context: Context): Boolean {
      try {
        val connectivityManager =
          context.getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val network = connectivityManager.activeNetwork
        val networkCapabilities = connectivityManager.getNetworkCapabilities(network)

        if (networkCapabilities != null &&
          networkCapabilities.hasCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET)
        ) {
          println("có mạng")
          return true
        }

        println("không mạng")
        Toast.makeText(context, "Không có kết nối mạng", Toast.LENGTH_SHORT).show()
        context.startActivity(Intent(context, LocalStoryActivity::class.java))
        return false
      } catch (e: Throwable) {
        println("/------CÓ LỖI----------/\n $e\n/------CÓ LỖI------------/")
        return false
      }
    }

    suspend fun saveImageFromUrl(context: Context, imageUrl: String, fileName: String): String? {
      val imagesDirectory = File(context.getExternalFilesDir(null), "images")
      if (!imagesDirectory.exists()) {
        imagesDirectory.mkdir()
      }
      val storiesDirectory = File(context.getExternalFilesDir(null), "images/stories")
      if (!storiesDirectory.exists()) {
        storiesDirectory.mkdir()
      }

      val url = URL(imageUrl)
      val file = File(storiesDirectory, "$fileName.png")

      return withContext(Dispatchers.IO) {
        try {
          val inputStream = url.openStream()
          val outputStream = FileOutputStream(file)
          val buffer = ByteArray(1024)
          var len = inputStream.read(buffer)
          while (len != -1) {
            outputStream.write(buffer, 0, len)
            len = inputStream.read(buffer)
          }
          inputStream.close()
          outputStream.close()

          file.absolutePath
        } catch (e: Exception) {
          println("/------CÓ LỖI----------/\n $e\n/------CÓ LỖI------------/")
          null
        }
      }
    }
  }
}