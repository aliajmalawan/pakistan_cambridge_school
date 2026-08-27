(function(){
const t=document.querySelector('[data-nav-toggle]'),m=document.querySelector('[data-nav-menu]');
if(t&&m){t.addEventListener('click',()=>{const o=t.getAttribute('aria-expanded')==='true';t.setAttribute('aria-expanded',String(!o));m.classList.toggle('open',!o);document.body.classList.toggle('menu-open',!o)});m.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{t.setAttribute('aria-expanded','false');m.classList.remove('open');document.body.classList.remove('menu-open')}));}
const h=document.getElementById('siteHeader'),s=()=>h&&h.classList.toggle('scrolled',scrollY>12);addEventListener('scroll',s,{passive:true});s();
document.querySelectorAll('[data-dropdown-toggle]').forEach(btn=>btn.addEventListener('click',e=>{if(window.innerWidth<=760){e.preventDefault();const box=btn.closest('[data-dropdown]');const open=box.classList.toggle('open');btn.setAttribute('aria-expanded',String(open));}}));
document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',function(e){const x=document.querySelector(this.getAttribute('href'));if(x){e.preventDefault();x.scrollIntoView({behavior:'smooth',block:'start'})}}));

const heroWrap=document.querySelector('[data-hero-slider]');
if(heroWrap){
  const slides=[...heroWrap.querySelectorAll('[data-slide]')];
  const dots=[...heroWrap.querySelectorAll('[data-hero-dot]')];
  if(slides.length>1){
    let idx=0,timer;
    const show=i=>{
      slides[idx].classList.remove('active');
      if(dots[idx])dots[idx].classList.remove('active');
      idx=(i+slides.length)%slides.length;
      slides[idx].classList.add('active');
      if(dots[idx])dots[idx].classList.add('active');
    };
    const restart=()=>{clearInterval(timer);timer=setInterval(()=>show(idx+1),6500)};
    heroWrap.querySelector('[data-hero-next]')?.addEventListener('click',()=>{show(idx+1);restart()});
    heroWrap.querySelector('[data-hero-prev]')?.addEventListener('click',()=>{show(idx-1);restart()});
    dots.forEach((d,i)=>d.addEventListener('click',()=>{show(i);restart()}));
    if(!matchMedia('(prefers-reduced-motion: reduce)').matches)restart();
  }
}

const classSearch=document.getElementById('classSubjectSearch');
if(classSearch){
  const rows=[...document.querySelectorAll('#classSubjectTable tbody tr')];
  const empty=document.getElementById('classSubjectEmpty');
  classSearch.addEventListener('input',()=>{
    const q=classSearch.value.trim().toLowerCase();
    let shown=0;
    rows.forEach(r=>{
      const match=r.dataset.className.includes(q);
      r.hidden=!match;
      if(match)shown++;
    });
    if(empty)empty.hidden=shown!==0;
  });
}

const admForm=document.querySelector('[data-admission-form]');
if(admForm){
  const panels=[...admForm.querySelectorAll('.af-panel')];
  const stepEls=[...document.querySelectorAll('[data-step-indicator]')];
  const card=admForm.closest('.admission-form-card');
  let current=1;
  const escapeHtml=s=>{const d=document.createElement('div');d.textContent=s;return d.innerHTML};

  const labels={student_name:"Student's Name",father_name:"Father's Name",bform:'B-Form / CNIC',
    dob:'Date of Birth',gender:'Gender',guardian_phone:'Guardian Phone',address:'Home Address',
    class_applied:'Class Applying For',prev_school:'Previous School',notes:'Notes'};
  const wideFields=['address','notes'];

  function buildReview(){
    const review=admForm.querySelector('[data-af-review]');
    let html='';
    Object.keys(labels).forEach(name=>{
      const field=admForm.querySelector(`[name="${name}"]`);
      if(!field)return;
      let value='';
      if(field.type==='radio'){
        const checked=admForm.querySelector(`[name="${name}"]:checked`);
        value=checked?checked.value.charAt(0).toUpperCase()+checked.value.slice(1):'';
      }else{
        value=field.value;
      }
      if(!value)return;
      html+=`<div class="af-review-item${wideFields.includes(name)?' af-wide':''}"><span>${labels[name]}</span><b>${escapeHtml(value)}</b></div>`;
    });
    review.innerHTML=html||'<p>Please go back and fill in the form.</p>';
  }

  function showStep(n){
    panels.forEach(p=>p.hidden=Number(p.dataset.step)!==n);
    stepEls.forEach(s=>{
      const i=Number(s.dataset.stepIndicator);
      s.classList.toggle('active',i===n);
      s.classList.toggle('done',i<n);
    });
    if(n===3)buildReview();
    current=n;
    if(card)card.scrollIntoView({behavior:'smooth',block:'start'});
  }

  function validateStep(n){
    const panel=panels.find(p=>Number(p.dataset.step)===n);
    const seen=new Set();
    for(const f of panel.querySelectorAll('input,select,textarea')){
      if(!f.required||seen.has(f.name))continue;
      if(f.type==='radio'){
        seen.add(f.name);
        const group=panel.querySelectorAll(`input[name="${f.name}"]`);
        if(![...group].some(r=>r.checked)){alert('Please select a gender.');return false;}
        continue;
      }
      if(!f.checkValidity()){f.reportValidity();return false;}
    }
    return true;
  }

  admForm.querySelectorAll('[data-af-next]').forEach(btn=>btn.addEventListener('click',()=>{
    if(validateStep(current))showStep(current+1);
  }));
  admForm.querySelectorAll('[data-af-back]').forEach(btn=>btn.addEventListener('click',()=>showStep(current-1)));
}

const galleryFilters=[...document.querySelectorAll('[data-gallery-filter]')];
const galleryGrid=document.querySelector('[data-gallery-grid]');
if(galleryFilters.length&&galleryGrid){
  const items=[...galleryGrid.querySelectorAll('[data-gallery-item]')];
  galleryFilters.forEach(btn=>btn.addEventListener('click',()=>{
    galleryFilters.forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    const filter=btn.dataset.galleryFilter;
    items.forEach(item=>item.classList.toggle('gallery-hidden',filter!=='all'&&item.dataset.galleryItem!==filter));
  }));
}

const reveals=document.querySelectorAll('[data-reveal],[data-reveal-group]');
if(reveals.length){
  if('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches){
    const io=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('revealed');io.unobserve(entry.target);}});},{threshold:.12,rootMargin:'0px 0px -60px 0px'});
    reveals.forEach(el=>io.observe(el));
  } else {
    reveals.forEach(el=>el.classList.add('revealed'));
  }
}
})();
