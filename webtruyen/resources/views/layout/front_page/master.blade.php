<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="fb:admins" content="&#123;100048327580198&#125;"/>
    <meta property="fb:admins" content="&#123;YOUR_FACEBOOK_USER_ID_2&#125;"/>
    <script src="https://kit.fontawesome.com/08c104de7d.js" crossorigin="anonymous"></script>
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front_asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front_asset/css/style.css') }}">
    <link rel="icon" href="{{ asset('/img/page/icon.png') }}">
    @stack('css')
    <title>{{ $title ?? env('APP_NAME') . ' - cộng đồng những người đam mê truyện chữ' }}</title>
</head>

<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v15.0&appId=653129892978610&autoLogAppEvents=1"
        nonce="MsgWl4XH">
</script>
    @include('layout.front_page.header')
    <link rel="stylesheet" href="{{ asset('front_asset/css/index/index.css') }}">
    <main>
        <div class="container">
            @yield('main')
        </div>
    </main>
    <footer>
        <div class="container">
            @include('layout.front_page.footer')
        </div>
    </footer>

    <script src="{{ asset('front_asset/js/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('front_asset/js/popper.min.js') }}"></script>
    <script src="{{ asset('front_asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front_asset/js/script.js') }}"></script>
    {{-- show search box --}}
    <script>
        const inputSearchDesk = document.getElementById('input_search_desk');
        const autoBoxDesk = document.getElementById('autoBox_desk')
        const inputSearchMob = document.getElementById('inputSearchMob');
        const autoboxMob = document.getElementById('autoboxMob');


        // danh sách truyện
        const listSearchStory = {!! json_encode(listStoriesSearch()) !!};

        inputSearchDesk.onkeyup = (e) => {
            renderSearch(e, inputSearchDesk, autoBoxDesk)
        }
        inputSearchMob.onkeyup = (e) => {
            renderSearch(e, inputSearchMob, autoboxMob)
        }
        function renderSearch(value, input, box) {
            let checkData = value.target.value
            let dataArr = []
            if (checkData) {
                dataArr = listSearchStory.filter((story) => {
                    return (story['name'].toLocaleLowerCase().search(checkData.toLocaleLowerCase())) !== -1;
                })

                dataArr = dataArr.map((story) => {
                    return story = `<li>
                                           <a href="/truyen/${story['slug']}" style="display: flex;">
                                                <img src="${story['image']}" alt="${story['name']}">
                                                <div class="ml-2">
                                                    <h4 class="search_name">${story['name']}</h4>
                                                    <p class="search_chapter"> Chương ${story['chapter_new']}</p>
                                                    <p class="search_author">${story['author']}</p>
                                                    <p class="search_categories">${story['category_name']}</p>
                                                </div>
                                           </a>
                                    </li>`
                })
                box.classList.add('active')
                console.log(dataArr);
                showSearch(dataArr, input, box)
            } else {
                box.classList.remove('active')
            }
        }

        function showSearch(list, input, box) {
            let listData
            if (!list.length) {
                listData = '<li>' + input.value + '</li>'
            } else {
                listData = list.join('')
            }
            box.innerHTML = listData
        }
    </script>
    @stack('js')

</body>

</html>
