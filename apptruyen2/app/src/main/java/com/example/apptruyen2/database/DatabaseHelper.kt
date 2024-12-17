package com.example.apptruyen2.database

import android.content.Context
import android.database.sqlite.SQLiteDatabase
import android.database.sqlite.SQLiteOpenHelper

class DatabaseHelper(context: Context) :
  SQLiteOpenHelper(context, DATABASE_NAME, null, DATABASE_VERSION) {

  override fun onCreate(db: SQLiteDatabase?) {
    db?.execSQL(SQL_CREATE_TABLE_STORIES)
    db?.execSQL(SQL_CREATE_TABLE_CHAPTERS)
  }

  override fun onUpgrade(db: SQLiteDatabase?, oldVersion: Int, newVersion: Int) {
    db?.execSQL(SQL_DELETE_TABLE_STORIES)
    db?.execSQL(SQL_DELETE_TABLE_CHAPTERS)
    onCreate(db)
  }

  companion object {
    const val DATABASE_VERSION = 1
    const val DATABASE_NAME = "WebTruyen"

    private const val SQL_CREATE_TABLE_STORIES =
      "CREATE TABLE stories (" +
        "id INTEGER PRIMARY KEY AUTOINCREMENT," +
        "name TEXT, " +
        "image TEXT, " +
        "slug TEXT)"

    private const val SQL_CREATE_TABLE_CHAPTERS =
      "CREATE TABLE chapters (" +
        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
        "number INTEGER, " +
        "name TEXT, " +
        "content TEXT, " +
        "story_id INTEGER, " +
        "FOREIGN KEY(story_id) REFERENCES stories(id) ON DELETE CASCADE)"

    private const val SQL_DELETE_TABLE_STORIES = "DROP TABLE IF EXISTS stories"
    private const val SQL_DELETE_TABLE_CHAPTERS = "DROP TABLE IF EXISTS chapters"
  }
}
