/* ---------- THEME ---------- */
(function(){
  var root=document.documentElement,
      btn=document.getElementById('themeBtn'),
      ico=document.getElementById('themeIcon'),
      txt=document.getElementById('themeTxt'),
      saved=null;
  if(!btn) return;
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
    navigator.clipboard.writeText(pre.innerText).then(function(){
      var old=b.textContent; b.textContent='Պատճենվեց';
      setTimeout(function(){ b.textContent=old; },1400);
    }).catch(function(){ b.textContent='Չստացվեց'; });
  });
});

/* ---------- LIVE PREVIEW ----------
   Ամեն .live բլոկ ինքնուրույն է։
   Կարող է ունենալ HTML դաշտ, CSS դաշտ կամ երկուսը։            */
document.querySelectorAll('.live').forEach(function(box){

  var tabs   = box.querySelectorAll('.live__tab'),
      panes  = box.querySelectorAll('.live__code'),
      frame  = box.querySelector('.live__out'),
      resetB = box.querySelector('.live__reset'),
      timer  = null,
      initial = {};

  panes.forEach(function(p){ initial[p.dataset.pane] = p.value; });

  function get(name){
    var el = box.querySelector('.live__code[data-pane="'+name+'"]');
    return el ? el.value : '';
  }

  function build(){
    var html = get('html');
    var css  = get('css');

    return '<!DOCTYPE html><html lang="hy"><head><meta charset="UTF-8">'
      + '<meta name="viewport" content="width=device-width, initial-scale=1">'
      + '<style>'
      + 'html{font-family:system-ui,"Noto Sans Armenian",sans-serif;font-size:15px;line-height:1.5}'
      + 'body{margin:0;padding:14px}'
      + '</style>'
      + '<style>' + css + '</style>'
      + '</head><body>' + html + '</body></html>';
  }

  function render(){
    frame.srcdoc = build();
  }

  function schedule(){
    clearTimeout(timer);
    timer = setTimeout(render, 350);
  }

  /* Ներդիրների փոխարկում */
  tabs.forEach(function(tab){
    tab.addEventListener('click',function(){
      tabs.forEach(function(t){ t.classList.remove('is-active'); });
      tab.classList.add('is-active');

      panes.forEach(function(p){
        p.classList.toggle('is-hidden', p.dataset.pane !== tab.dataset.pane);
      });
    });
  });

  /* Ինքնաբերաբար թարմացում գրելիս */
  panes.forEach(function(p){
    p.addEventListener('input', schedule);
  });

  /* Վերականգնում */
  if(resetB){
    resetB.addEventListener('click',function(){
      panes.forEach(function(p){ p.value = initial[p.dataset.pane]; });
      render();
    });
  }

  render();
});
