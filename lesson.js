/* ---------- THEME ---------- */
(function(){
  var root=document.documentElement,
      btn=document.getElementById('themeBtn'),
      ico=document.getElementById('themeIcon'),
      txt=document.getElementById('themeTxt'),
      saved=null;
  try{ saved=localStorage.getItem('lesson-theme'); }catch(e){}
  if(!saved && window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches) saved='dark';
  apply(saved||'light');

  function apply(t){
    root.setAttribute('data-theme',t);
    ico.textContent = t==='dark' ? '☀' : '☾';
    txt.textContent = t==='dark' ? 'Բաց' : 'Մուգ';
    try{ localStorage.setItem('lesson-theme',t); }catch(e){}
  }
  btn.addEventListener('click',function(){
    apply(root.getAttribute('data-theme')==='dark'?'light':'dark');
  });
})();

/* ---------- COPY ---------- */
document.querySelectorAll('.copy').forEach(function(b){
  b.addEventListener('click',function(){
    var pre=b.closest('.block').querySelector('pre');
    var text=pre.innerText;
    navigator.clipboard.writeText(text).then(function(){
      var old=b.textContent; b.textContent='Պատճենվեց';
      setTimeout(function(){ b.textContent=old; },1400);
    }).catch(function(){ b.textContent='Չստացվեց'; });
  });
});

/* ---------- PLAYGROUND ---------- */
(function(){
  var code=document.getElementById('pgCode'),
      out=document.getElementById('pgOut'),
      run=document.getElementById('runBtn'),
      clr=document.getElementById('clrBtn');

  function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  function fmt(v){
    if(typeof v==='string') return '<span class="r">'+esc(v)+'</span>';
    if(typeof v==='number') return '<span class="r">'+esc(v)+'</span>';
    if(typeof v==='boolean') return '<span class="g">'+v+'</span>';
    if(v===undefined) return '<span class="m">undefined</span>';
    if(v===null) return '<span class="m">null</span>';
    try{ return '<span class="r">'+esc(JSON.stringify(v))+'</span>'; }
    catch(e){ return '<span class="r">'+esc(String(v))+'</span>'; }
  }

  run.addEventListener('click',function(){
    var lines=['<span class="m">Console</span>'];
    var fake={
      log:function(){ lines.push(Array.prototype.map.call(arguments,fmt).join(' ')); },
      error:function(){ lines.push('<span class="e">'+esc(Array.prototype.join.call(arguments,' '))+'</span>'); },
      warn:function(){ lines.push('<span class="m">'+esc(Array.prototype.join.call(arguments,' '))+'</span>'); }
    };
    try{
      new Function('console', code.value)(fake);
    }catch(err){
      lines.push('<span class="e">'+esc(err.name+': '+err.message)+'</span>');
    }
    if(lines.length===1) lines.push('<span class="m">(ոչինչ չտպվեց)</span>');
    out.innerHTML=lines.join('\n');
  });

  clr.addEventListener('click',function(){
    out.innerHTML='<span class="m">Console</span>';
  });
})();
