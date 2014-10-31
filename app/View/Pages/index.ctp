<div class="row">
  <div class="small-4 columns">
    <div class="row">
      <p class="text-center">WoPict Login</p>
    </div>
    <?php if(!empty($error)) :?>
    <div class="row">
      <label >用户名或密码错误</label>
    </div>
    <?php endif; ?>
    <form method="post">
      <div class="row collapse prefix-radius">
        <div class="small-2 columns">
          <span class="prefix">邮箱</span>
        </div>
        <div class="small-10 columns">
          <input type="text" placeholder="input your email">
        </div>
      </div>
      <div class="row collapse prefix-radius">
        <div class="small-2 columns">
          <span class="prefix">密码</span>
        </div>
        <div class="small-10 columns">
          <input type="password" placeholder="input your password">
        </div>
      </div>
      <div class="row">
        <div class="small-10 small-centered columns">
          <button class="button expand round" type="submit">登录</button>
        </div>
      </div>
    </form>
  </div>
</div>
