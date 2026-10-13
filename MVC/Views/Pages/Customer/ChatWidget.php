<style>
.shop-ai-launch{position:fixed;right:24px;bottom:24px;z-index:1200;border:0;border-radius:999px;padding:13px 19px;background:linear-gradient(135deg,#e31d2b,#ff4b2b);color:#fff;font-weight:700;box-shadow:0 8px 24px rgba(180,30,40,.3);cursor:pointer}
.shop-ai-panel{position:fixed;right:24px;bottom:82px;z-index:1200;width:min(390px,calc(100vw - 28px));height:min(560px,calc(100vh - 115px));display:none;flex-direction:column;overflow:hidden;background:#fff;border:1px solid #e5eaf0;border-radius:18px;box-shadow:0 18px 48px rgba(24,34,48,.22)}
.shop-ai-panel.is-open{display:flex}.shop-ai-head{display:flex;align-items:center;justify-content:space-between;padding:15px 17px;background:linear-gradient(135deg,#e31d2b,#ff4b2b);color:#fff}.shop-ai-head strong{font-size:16px}.shop-ai-close{border:0;background:transparent;color:#fff;font-size:24px;cursor:pointer;line-height:1}.shop-ai-log{flex:1;overflow-y:auto;padding:15px;background:#f8fafc}.shop-ai-message{max-width:90%;margin:0 0 12px;padding:10px 12px;border-radius:13px;white-space:pre-line;overflow-wrap:anywhere;font-size:14px}.shop-ai-message.assistant{background:#fff;border:1px solid #e8edf3;color:#263244;border-top-left-radius:4px}.shop-ai-message.user{margin-left:auto;background:#e31d2b;color:#fff;border-top-right-radius:4px}.shop-ai-products{display:grid;gap:8px;margin:0 0 12px}.shop-ai-product{padding:10px 11px;background:#fff;border:1px solid #e5eaf0;border-radius:12px;font-size:13px}.shop-ai-product strong{display:block;margin-bottom:3px;color:#182230}.shop-ai-product span{display:block;color:#64748b}.shop-ai-product a{display:inline-block;margin-top:7px;color:#d7192d;font-weight:700}.shop-ai-compose{display:flex;gap:8px;padding:12px;border-top:1px solid #e5eaf0;background:#fff}.shop-ai-compose input{min-width:0;flex:1;border:1px solid #d6dee8;border-radius:10px;padding:10px 11px;font:inherit;font-size:14px}.shop-ai-compose button{border:0;border-radius:10px;padding:0 14px;background:#e31d2b;color:#fff;font-weight:700;cursor:pointer}.shop-ai-compose button:disabled{opacity:.6;cursor:wait}@media(max-width:640px){.shop-ai-launch{right:14px;bottom:14px}.shop-ai-panel{right:14px;bottom:72px}}
</style>
<section id="shop-ai-panel" class="shop-ai-panel" aria-label="Trợ lý tư vấn mua sắm" aria-hidden="true">
    <div class="shop-ai-head"><strong>Trợ lý tư vấn Win Mart</strong><button type="button" id="shop-ai-close" class="shop-ai-close" aria-label="Đóng">&times;</button></div>
    <div id="shop-ai-log" class="shop-ai-log" role="log" aria-live="polite">
        <div class="shop-ai-message assistant">Chào bạn! Hãy cho mình biết ngân sách, sản phẩm và số lượng muốn mua. Ví dụ: “Mình có 200.000đ, cần 1 cây lau nhà và 3 cái bát”.</div>
    </div>
    <form id="shop-ai-form" class="shop-ai-compose">
        <input id="shop-ai-input" type="text" maxlength="1000" autocomplete="off" placeholder="Nhập nhu cầu mua sắm..." aria-label="Nhu cầu mua sắm">
        <button type="submit">Gửi</button>
    </form>
</section>
<button id="shop-ai-launch" type="button" class="shop-ai-launch" aria-expanded="false" aria-controls="shop-ai-panel">✦ Tư vấn mua sắm</button>
<script>
(function(){
    const panel=document.getElementById('shop-ai-panel');
    const launch=document.getElementById('shop-ai-launch');
    const close=document.getElementById('shop-ai-close');
    const log=document.getElementById('shop-ai-log');
    const form=document.getElementById('shop-ai-form');
    const input=document.getElementById('shop-ai-input');
    const submit=form.querySelector('button[type="submit"]');
    const endpoint='store.php?url=Storefront/ChatRecommend';
    const csrf=<?php echo json_encode($_SESSION['shop_csrf'] ?? '', JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
    const customerScope=<?php echo json_encode($_SESSION['shop_customer']['MaKH'] ?? 'guest', JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
    const historyKey='winmart_shop_assistant_v1_'+customerScope;
    let history=[];
    try{const saved=JSON.parse(localStorage.getItem(historyKey)||'[]');if(Array.isArray(saved))history=saved;}catch(error){history=[];}
    function setOpen(open){panel.classList.toggle('is-open',open);panel.setAttribute('aria-hidden',String(!open));launch.setAttribute('aria-expanded',String(open));if(open)input.focus();}
    function saveHistory(){history=history.slice(-120);try{localStorage.setItem(historyKey,JSON.stringify(history));}catch(error){}}
    function addMessage(text,kind,save=true){const item=document.createElement('div');item.className='shop-ai-message '+kind;item.textContent=text;log.appendChild(item);log.scrollTop=log.scrollHeight;if(save){history.push({type:'message',text:String(text),kind:kind});saveHistory();}return item;}
    function addProducts(items,save=true){if(!Array.isArray(items)||!items.length)return;const safeItems=items.map(product=>({id:String(product.id),name:String(product.name),category:String(product.category||'Sản phẩm'),price:Number(product.price)||0,stock:Number(product.stock)||0,quantity:Number(product.quantity)||1}));const wrap=document.createElement('div');wrap.className='shop-ai-products';safeItems.forEach(product=>{const card=document.createElement('div');card.className='shop-ai-product';const title=document.createElement('strong');title.textContent=product.name;const category=document.createElement('span');category.textContent=product.category+' · '+new Intl.NumberFormat('vi-VN').format(product.price)+'đ / sản phẩm';const quantity=document.createElement('span');quantity.textContent='Gợi ý: '+product.quantity+' · Còn '+product.stock+' sản phẩm';const link=document.createElement('a');link.href='store.php?url=Storefront/ChiTiet/'+encodeURIComponent(product.id);link.textContent='Xem sản phẩm';card.append(title,category,quantity,link);wrap.appendChild(card);});log.appendChild(wrap);log.scrollTop=log.scrollHeight;if(save){history.push({type:'products',items:safeItems});saveHistory();}}
    function restoreHistory(){if(!history.length)return;log.replaceChildren();history.forEach(event=>{if(event&&event.type==='message'&&typeof event.text==='string'&&(event.kind==='user'||event.kind==='assistant'))addMessage(event.text,event.kind,false);else if(event&&event.type==='products')addProducts(event.items,false);});log.scrollTop=log.scrollHeight;}
    restoreHistory();
    launch.addEventListener('click',()=>setOpen(!panel.classList.contains('is-open')));close.addEventListener('click',()=>setOpen(false));
    form.addEventListener('submit',async event=>{event.preventDefault();const message=input.value.trim();if(!message)return;addMessage(message,'user');input.value='';submit.disabled=true;submit.textContent='…';const loading=addMessage('Đang tìm lựa chọn phù hợp…','assistant',false);try{const body=new URLSearchParams({csrf_token:csrf,message});const response=await fetch(endpoint,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8','X-Requested-With':'XMLHttpRequest'},body});const result=await response.json();loading.remove();if(!response.ok)throw new Error(result.error||'Chưa thể xử lý câu hỏi.');addMessage(result.reply||'Mình chưa có câu trả lời phù hợp.','assistant');addProducts(result.items);}catch(error){loading.remove();addMessage(error.message||'Không kết nối được tới dịch vụ tư vấn. Vui lòng thử lại.','assistant');}finally{submit.disabled=false;submit.textContent='Gửi';input.focus();}});
})();
</script>
