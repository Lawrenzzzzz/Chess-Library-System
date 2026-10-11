(function(){
 const $=s=>document.querySelector(s), $$=s=>[...document.querySelectorAll(s)];
 const app=$('#app');
 const ls={get:k=>{try{return localStorage.getItem(k)}catch(e){return null}},set:(k,v)=>{try{localStorage.setItem(k,v)}catch(e){}},del:k=>{try{localStorage.removeItem(k)}catch(e){}}};
 // sidebar
 if(ls.get('cr-collapsed')==='1'&&innerWidth>640)app.classList.add('collapsed');
 $('#menu-toggle').onclick=()=>{ if(innerWidth<=640)app.classList.toggle('mobile-open'); else{app.classList.toggle('collapsed');ls.set('cr-collapsed',app.classList.contains('collapsed')?'1':'0');} };
 $('#overlay').onclick=()=>app.classList.remove('mobile-open');
 // profile
 const name=ls.get('cr-name'); if(name){$('#profile-name').textContent=name;$('#profile-initials').textContent=name.split(/\s+/).map(w=>w[0]).join('').slice(0,2).toUpperCase();}
 // likes & comments
 $$('.like-btn').forEach(b=>b.onclick=()=>{const on=b.classList.toggle('liked');b.querySelector('span').textContent=+b.dataset.count+(on?1:0);});
 $$('.comment-toggle').forEach(b=>b.onclick=()=>b.closest('.post').querySelector('.comments').classList.toggle('open'));
 $$('.comment-send').forEach(b=>b.onclick=()=>{const i=b.previousElementSibling,v=i.value.trim();if(!v)return;const d=document.createElement('div');d.className='comment-row';d.textContent=v;b.closest('.comments').insertBefore(d,b.closest('.comment-box'));i.value='';});
 // share book modal
 const m=$('#share-modal');
 if(m){
  $('#open-share').onclick=()=>m.hidden=false; $('#close-share').onclick=()=>m.hidden=true;
  m.onclick=e=>{if(e.target===m)m.hidden=true;};
  $('#b-add').onclick=()=>{const t=$('#b-title').value.trim(),a=$('#b-author').value.trim();if(!t||!a)return;
   const d=document.createElement('div');d.className='library-item';
   d.innerHTML='<div class="book-cover"><span></span><b>♟</b><span>CHESS</span></div><h3></h3><p></p><div class="book-info"><small>Shared by <span>You</span></small></div>';
   d.querySelector('span').textContent=a.split(' ')[0].toUpperCase();d.querySelector('h3').textContent=t;d.querySelector('p').textContent=a;
   $('#library').prepend(d);$('#b-title').value=$('#b-author').value='';m.hidden=true;};
 }
 // settings
 if($('#s-save')){
  $('#s-name').value=ls.get('cr-name')||'';$('#s-bio').value=ls.get('cr-bio')||'';
  $('#s-save').onclick=()=>{ls.set('cr-name',$('#s-name').value.trim());ls.set('cr-bio',$('#s-bio').value.trim());$('#s-msg').textContent='Saved.';setTimeout(()=>location.reload(),400);};
  $('#s-reset').onclick=()=>{['cr-name','cr-bio','cr-collapsed'].forEach(ls.del);location.reload();};
 }
 // puzzles
 const board=$('#board');
 if(board){
  const G={K:'♔',Q:'♕',R:'♖',B:'♗',N:'♘',P:'♙',k:'♚',q:'♛',r:'♜',b:'♝',n:'♞',p:'♟'};
  const puzzles=[
   {t:'Back-rank mate',d:'White to move and win.',fen:'6k1/5ppp/8/8/8/8/5PPP/3R2K1',s:'1. Rd8# — the king is trapped by its own pawns.'},
   {t:'Queen and king mate',d:'White to move. Mate in one.',fen:'7k/5Q2/6K1/8/8/8/8/8',s:'1. Qg7# — the queen is protected by the king.'},
   {t:'Smothered mate',d:'White to move. Mate in one.',fen:'6rk/6pp/8/6N1/8/8/8/6K1',s:'1. Nf7# — the king is smothered by its own rook and pawn.'}];
  let i=0,sel=null;
  function draw(){const p=puzzles[i];$('#p-title').textContent=p.t;$('#p-desc').textContent=p.d;$('#p-solution').textContent=p.s;$('#p-solution').hidden=true;$('#p-show').textContent='Show solution';
   board.innerHTML='';sel=null;
   p.fen.split('/').forEach((row,r)=>{let c=0;for(const ch of row){if(/\d/.test(ch)){for(let k=0;k<+ch;k++)add(r,c++,'');}else add(r,c++,G[ch]);}});}
  function add(r,c,g){const s=document.createElement('div');s.className='square'+((r+c)%2?' dark':'');s.textContent=g;
   s.onclick=()=>{if(sel)sel.classList.remove('selected');sel=s;s.classList.add('selected');};board.appendChild(s);}
  $('#p-show').onclick=()=>{const s=$('#p-solution');s.hidden=!s.hidden;$('#p-show').textContent=s.hidden?'Show solution':'Hide solution';};
  $('#p-next').onclick=()=>{i=(i+1)%puzzles.length;draw();};
  draw();
 }
})();
