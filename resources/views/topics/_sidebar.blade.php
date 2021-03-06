{{--<div class="fly-panel fly-signin">--}}
{{--    <div class="fly-panel-title">--}}
{{--        反馈--}}
{{--        <i class="fly-mid"></i>--}}
{{--        <a href="javascript:;" class="fly-link" id="LAY_signinHelp">更新<span class="layui-badge-dot"></span></a>--}}
{{--        <span class="fly-signin-days">v 1.0.0</span>--}}
{{--    </div>--}}
{{--    <div class="fly-panel-main fly-signin-main">--}}
{{--        <button class="layui-btn layui-btn-danger" id="LAY_signin">BUG 反馈</button>--}}

{{--        <!-- 已签到状态 -->--}}
{{--        <!----}}
{{--        <button class="layui-btn layui-btn-disabled">今日已签到</button>--}}
{{--        <span>获得了<cite>20</cite>飞吻</span>--}}
{{--        -->--}}
{{--    </div>--}}
{{--</div>--}}

@if (count($active_users))
    <div class="fly-panel fly-rank fly-rank-reply" id="LAY_replyRank">
        <h3 class="fly-panel-title">活跃用户</h3>
        <dl>
            <!--<i class="layui-icon fly-loading">&#xe63d;</i>-->
            @foreach ($active_users as $active_user)
                <dd>
                    <a href="{{ route('users.show', $active_user->id) }}">
                        <img
                            src="{{ $active_user->avatar }}"><cite>{{ $active_user->name }}</cite><i>{{ $active_user->name }}</i>
                    </a>
                </dd>
            @endforeach
        </dl>
    </div>
@endif

<div class="fly-panel">
    <div class="fly-panel-title">
        感谢
    </div>
    <div class="fly-panel-main">
        <a href="https://www.layui.com" target="_blank" class="fly-zanzhu" style="background-color: #5FB878;">Layui -
            经典模块化前端 UI 框架</a>
        <a href="https://learnku.com/laravel" target="_blank" class="fly-zanzhu" style="background-color: #393D49;">Laravel
            China - 高品质的 Laravel 社区</a>
    </div>
</div>

{{--@if (count($links))--}}
{{--    <dl class="fly-panel fly-list-one">--}}
{{--        <dt class="fly-panel-title">资源推荐</dt>--}}
{{--        @foreach ($links as $link)--}}
{{--            <dd>--}}
{{--                <a href="{{ $link->link }}">{{ $link->title }}</a>--}}
{{--                <span><i class="iconfont icon-pinglun1"></i> 16</span>--}}
{{--            </dd>--}}
{{--    @endforeach--}}

{{--    <!-- 无数据时 -->--}}
{{--        <!----}}
{{--        <div class="fly-none">没有相关数据</div>--}}
{{--        -->--}}
{{--    </dl>--}}
{{--@endif--}}

@if (count($links))
    <div class="fly-panel">
        <h3 class="fly-panel-title">推荐</h3>
        <ul class="fly-panel-main fly-list-static">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link->link }}" target="_blank">{{ $link->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="fly-panel">
    <img src="{{ asset('res/images/qq.jpg') }}" style="max-width: 100%;border-radius: 20%"
         alt="layui">
</div>
