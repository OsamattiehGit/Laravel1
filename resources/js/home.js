document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.carousel-container');
    const track     = container?.querySelector('.carousel-track');
    const slides    = track ? Array.from(track.children) : [];
    const dots      = container ? Array.from(container.querySelectorAll('.carousel-dot')) : [];
    if (!container || !track || slides.length === 0) return;
  
    let index = 0, anim = false, timer;
  
    const gapPx  = () => parseFloat(getComputedStyle(track).gap || 0) || 0;
    const stepPx = () => slides[0].getBoundingClientRect().width + gapPx();
    const peekPx = () => parseFloat(getComputedStyle(container).getPropertyValue('--peek')) || 0;
  
    const offsetFor = (i) => {
      // base distance
      let px = i * stepPx();
      // avoid sub-pixel gaps
      return Math.round(px);
    };
  
    function go(to){
      if (anim) return;
      index = (to + slides.length) % slides.length;
      anim = true;
      track.style.transform = `translateX(${-offsetFor(index)}px)`;
      dots.forEach((d,i)=>d.classList.toggle('active', i===index));
      setTimeout(()=>anim=false, 600);
    }
  
    const next = () => go(index + 1);
    const prev = () => go(index - 1);
  
    dots.forEach((dot,i)=>dot.addEventListener('click',()=>go(i)));
  
    // touch / drag
    let startX=0, dragging=false;
    container.addEventListener('touchstart', e=>{ startX=e.touches[0].clientX; stop(); }, {passive:true});
    container.addEventListener('touchend',   e=>{
      const dx = startX - e.changedTouches[0].clientX;
      if (Math.abs(dx)>50) (dx>0?next():prev());
      start();
    }, {passive:true});
    container.addEventListener('mousedown', e=>{ dragging=true; startX=e.clientX; stop(); container.style.cursor='grabbing'; });
    document.addEventListener('mouseup', e=>{
      if (!dragging) return; dragging=false; container.style.cursor='grab';
      const dx = startX - e.clientX;
      if (Math.abs(dx)>50) (dx>0?next():prev());
      start();
    });
  
    function start(){ if (!timer) timer = setInterval(next, 2000); }
    function stop(){ if (timer){ clearInterval(timer); timer=null; } }
  
    container.addEventListener('mouseenter', stop);
    container.addEventListener('mouseleave', start);
  
    // keep alignment on resize
    window.addEventListener('resize', () => {
      track.style.transition = 'none';
      track.style.transform  = `translateX(${-offsetFor(index)}px)`;
      requestAnimationFrame(()=> track.style.transition = 'transform .6s ease');
    }, {passive:true});
  
    // init
    container.style.cursor='grab';
    track.style.transform='translateX(0)';
    dots.forEach((d,i)=>d.classList.toggle('active', i===0));
    start();
  });
  