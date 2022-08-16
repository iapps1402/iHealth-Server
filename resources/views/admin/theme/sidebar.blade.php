<!-- sidebar-->
<aside class="aside-container">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav class="sidebar" data-sidebar-anyclick-close="">
            <!-- START sidebar nav-->
            <ul class="sidebar-nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <div class="collapse" id="user-block">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status">
                                    <img class="img-thumbnail rounded-circle" src="/themes/angle/img/user/02.jpg"
                                         alt="Avatar" width="60" height="60">
                                    <div class="circle bg-success circle-lg"></div>
                                </div>
                            </div>
                            <!-- Name and Job-->
                            <div class="user-block-info">
                                <span class="user-block-name">{{ $admin->user->full_name }}</span>
                                <span class="user-block-role">{{ $admin->type == 'super' ? 'مدیر' : 'نویسنده' }}</span>
                                <span class="user-block-status"><a style="color: darkred;font-size: 9pt"
                                                                   href="{{ Route('admin_logout') }}">خروج</a> </span>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- END user info-->
                <!-- Iterates over all sidebar items-->
                <li class="nav-heading ">
                    <span data-localize="sidebar.heading.HEADER">منوی اصلی</span>
                </li>

                <li class="@if(Request()->url() === Route('admin_dashboard'))active @endif">
                    <a href="{{ Route('admin_dashboard') }}" title="میزکار">
                        <em class="icon-screen-desktop"></em>
                        <span data-localize="sidebar.nav.orders">میزکار</span>
                    </a>
                </li>

                <li class="@if(Request()->is('admin/food*'))active @endif">
                    <a href="#food" title="غذا" data-toggle="collapse">
                        <em class="icon-chart"></em>
                        <span data-localize="sidebar.nav.food">غذا</span>
                    </a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="food">
                        <li @if(Request()->url() === Route('admin_food_add')) class="active"@endif>
                            <a href="{{ Route('admin_food_add') }}" title="افزودن">
                                <span>افزودن</span>
                            </a>
                        </li>

                        <li @if(Request()->url() === Route('admin_food_manage')) class="active"@endif>
                            <a href="{{ Route('admin_food_manage') }}" title="مدیریت">
                                <span>مدیریت</span>
                            </a>
                        </li>

                        <li @if(Request()->url() === Route('admin_food_suggestion_manage')) class="active"@endif>
                            <a href="{{ Route('admin_food_suggestion_manage') }}" title="پیشنهاد ها">
                                <span>پیشنهاد ها</span>
                            </a>
                        </li>

                        <li @if(Request()->url() === Route('admin_food_category_manage')) class="active"@endif>
                            <a href="{{ Route('admin_food_category_manage') }}" title="دسته بندی ها">
                                <span>دسته بندی ها</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="@if(Request()->is('admin/post*'))active @endif">
                    <a href="#blog" title="وبلاگ" data-toggle="collapse">
                        <em class="icon-note"></em>
                        <span data-localize="sidebar.nav.blog">وبلاگ</span>
                    </a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="blog">
                        <li @if(Request()->url() === Route('admin_blog_post_add')) class="active"@endif>
                            <a href="{{ Route('admin_blog_post_add') }}" title="افزودن پست">
                                <span>افزودن پست</span>
                            </a>
                        </li>
                        <li @if(Request()->url() === Route('admin_blog_post_manage')) class="active"@endif>
                            <a href="{{ Route('admin_blog_post_manage') }}" title="مدیریت پست ها">
                                <span>مدیریت پست ها</span>
                            </a>
                        </li>
                        <li @if(Request()->url() === Route('admin_blog_post_categories', ['id' => 'root'])) class="active"@endif>
                            <a href="{{ Route('admin_blog_post_categories', ['id' => 'root']) }}"
                               title="مدیریت دسته بندی">
                                <span>دسته بندی ها</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="@if(Request()->is('admin/slider*'))active @endif">
                    <a href="#slider" title="اسلایدر" data-toggle="collapse">
                        <em class="icon-picture"></em>
                        <span data-localize="sidebar.nav.slider">اسلایدر</span>
                    </a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="slider">
                        <li class="@if(Request()->url() === Route('admin_slider_add'))active @endif">
                            <a href="{{ Route('admin_slider_add') }}" title="افزودن اسلایدر">
                                <span data-localize="sidebar.nav.slider">افزودن اسلایدر</span>
                            </a>
                        </li>

                        <li class="@if(Request()->url() === Route('admin_slider_manage'))active @endif">
                            <a href="{{ Route('admin_slider_manage') }}" title="مدیریت اسلایدر">
                                <span data-localize="sidebar.nav.slider">مدیریت اسلایدر</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="@if(Request()->is('admin/upload*'))active @endif">
                    <a href="#upload" title="آپلود" data-toggle="collapse">
                        <em class="icon-cloud-upload"></em>
                        <span data-localize="sidebar.nav.upload">آپلود</span>
                    </a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="upload">
                        <li @if(Request()->url() === Route('admin_upload_add')) class="active"@endif>
                            <a href="{{ Route('admin_upload_add') }}" title="آپلود">
                                <span>آپلود</span>
                            </a>
                        </li>

                        <li @if(Request()->url() === Route('admin_upload_manage')) class="active"@endif>
                            <a href="{{ Route('admin_upload_manage') }}" title="مدیریت">
                                <span>مدیریت</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="@if(Request()->is('admin/activity*'))active @endif">
                    <a href="#activity" title="فعالیت" data-toggle="collapse">
                        <em class="icon-chart"></em>
                        <span data-localize="sidebar.nav.activity">فعالیت</span>
                    </a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="activity">
                        <li @if(Request()->url() === Route('admin_activity_add')) class="active"@endif>
                            <a href="{{ Route('admin_activity_add') }}" title="افزودن">
                                <span>افزودن</span>
                            </a>
                        </li>
                        <li @if(Request()->url() === Route('admin_activity_manage')) class="active"@endif>
                            <a href="{{ Route('admin_activity_manage') }}" title="مدیریت">
                                <span>مدیریت</span>
                            </a>
                        </li>
                    </ul>
                </li>



{{--                <li class="@if(Request()->is('admin/upload*'))active @endif">--}}
{{--                    <a href="#upload" title="آپلود تصویر" data-toggle="collapse">--}}
{{--                        <em class="icon-cloud-upload"></em>--}}
{{--                        <span data-localize="sidebar.nav.upload">آپلود تصویر</span>--}}
{{--                    </a>--}}
{{--                    <ul class="sidebar-nav sidebar-subnav collapse" id="upload">--}}
{{--                        <li @if(Request()->url() === Route('admin_upload_add')) class="active"@endif>--}}
{{--                            <a href="{{ Route('admin_upload_add') }}" title="آپلود">--}}
{{--                                <span>آپلود</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                        <li @if(Request()->url() === Route('admin_upload_manage')) class="active"@endif>--}}
{{--                            <a href="{{ Route('admin_upload_manage') }}" title="مدیریت تصاویر">--}}
{{--                                <span>مدیریت</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}

{{--                <li class="@if(Request()->is('admin/agent*'))active @endif">--}}
{{--                    <a href="#agent" title="آپلود تصویر" data-toggle="collapse">--}}
{{--                        <em class="icon-user-follow"></em>--}}
{{--                        <span data-localize="sidebar.nav.agent">نمایندگی ها</span>--}}
{{--                    </a>--}}
{{--                    <ul class="sidebar-nav sidebar-subnav collapse" id="agent">--}}
{{--                        <li @if(Request()->url() === Route('admin_agent_add')) class="active"@endif>--}}
{{--                            <a href="{{ Route('admin_agent_add') }}" title="افزودن">--}}
{{--                                <span>افزودن</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                        <li @if(Request()->url() === Route('admin_agent_manage')) class="active"@endif>--}}
{{--                            <a href="{{ Route('admin_agent_manage') }}" title="">--}}
{{--                                <span>مدیریت</span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}

{{--                <li class="@if(Request()->url() === Route('admin_album_manage'))active @endif">--}}
{{--                    <a href="{{ Route('admin_album_manage') }}" title="مدیریت آلبوم ها">--}}
{{--                        <em class="icon-picture"></em>--}}
{{--                        <span data-localize="sidebar.nav.contact">مدیریت آلبوم ها</span>--}}
{{--                    </a>--}}
{{--                </li>--}}

{{--                @if($admin->type == 'super')--}}
{{--                    <li class="@if(Request()->url() === Route('admin_contact'))active @endif">--}}
{{--                        <a href="{{ Route('admin_contact') }}" title="تماس با ما">--}}
{{--                            @if($unreadContactsCount > 0)--}}
{{--                                <div class="float-right badge badge-warning"--}}
{{--                                     id="badge_contact">@if($unreadContactsCount > 9)--}}
{{--                                        +9 @else{{ $unreadContactsCount }}@endif</div>--}}
{{--                            @endif--}}
{{--                            <em class="icon-notebook"></em>--}}
{{--                            <span data-localize="sidebar.nav.contact">تماس با ما</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endif--}}

{{--                @if($admin->type == 'super')--}}
{{--                    <li class="@if(Request()->is('admin/admin*'))active @endif">--}}
{{--                        <a href="#admin" title="مدیران" data-toggle="collapse">--}}
{{--                            <em class="icon-user"></em>--}}
{{--                            <span data-localize="sidebar.nav.admin">مدیران</span>--}}
{{--                        </a>--}}
{{--                        <ul class="sidebar-nav sidebar-subnav collapse" id="admin">--}}
{{--                            <li @if(Request()->url() === Route('admin_agent_add')) class="active"@endif>--}}
{{--                                <a href="{{ Route('admin_admin_add') }}" title="افزودن">--}}
{{--                                    <span>افزودن</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li @if(Request()->url() === Route('admin_admin_manage')) class="active"@endif>--}}
{{--                                <a href="{{ Route('admin_admin_manage') }}" title="">--}}
{{--                                    <span>مدیریت</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                @endif--}}

{{--                @if($admin->type == 'super')--}}
{{--                    <li class="@if(Request()->is('admin/settings*'))active @endif">--}}
{{--                        <a href="#settings" title="تنظیمات" data-toggle="collapse">--}}
{{--                            <em class="icon-settings"></em>--}}
{{--                            <span data-localize="sidebar.nav.settings">تنظیمات</span>--}}
{{--                        </a>--}}
{{--                        <ul class="sidebar-nav sidebar-subnav collapse" id="settings">--}}
{{--                            <li @if(Request()->url() === Route('admin_settings_overall')) class="active"@endif>--}}
{{--                                <a href="{{ Route('admin_settings_overall') }}" title="تنظیمات کلی">--}}
{{--                                    <span>تنظیمات کلی</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                @endif--}}
            </ul>
        </nav>
    </div>
</aside>
