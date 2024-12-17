package com.example.apptruyen2.api

import com.google.gson.GsonBuilder
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.concurrent.TimeUnit

object RetrofitClient {
    private const val BASE_URL = "http://192.168.1.6:8000"
   // private const val BASE_URL = "http://192.168.43.246:8000"

    private val gson = GsonBuilder().create()

    fun create(token: String? = null): StoryApi {
        val clientBuilder = OkHttpClient.Builder()
            .connectTimeout(30, TimeUnit.SECONDS)
            .readTimeout(30, TimeUnit.SECONDS)

        if (token != null) {
            clientBuilder.addInterceptor { chain ->
                val original = chain.request()
                val requestBuilder = original.newBuilder()
                    .header("Authorization", "Bearer $token")
                    .method(original.method, original.body)
                val request = requestBuilder.build()
                chain.proceed(request)
            }
        }

        val client = clientBuilder.build()

        val retrofit = Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()

        return retrofit.create(StoryApi::class.java)
    }

}