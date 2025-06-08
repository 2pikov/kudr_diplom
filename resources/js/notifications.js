function showNotification(message) {
    const notification = document.getElementById('notification');
    const notificationText = notification.querySelector('.notification-text');
    
    notificationText.textContent = message;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

function showAuthModal() {
    const authModal = new bootstrap.Modal(document.getElementById('authModal'));
    authModal.show();
}

function toggleFavorite(event, productId) {
    event.preventDefault();
    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => {
        if (response.status === 401) {
            showAuthModal();
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.status === 'added') {
            event.target.closest('.btn-favorite, .btn-favorite2, .btn-favorite3, .btn-favorite-catalog').classList.add('active');
            showNotification('Товар добавлен в избранное');
        } else if (data && data.status === 'removed') {
            const productCard = event.target.closest('.card1, .hit-card, .product-card');
            
            if (window.location.pathname === '/favorites') {
                productCard.remove();
                
                const remainingProducts = document.querySelectorAll('.card1, .hit-card, .product-card');
                if (remainingProducts.length === 0) {
                    const productsGrid = document.querySelector('.products-grid');
                    productsGrid.innerHTML = `
                        <div class="no-favorites">
                            <p>В избранном пока нет товаров</p>
                            <a href="/catalog" class="btn-to-catalog">Перейти в каталог</a>
                        </div>
                    `;
                }
            } else {
                event.target.closest('.btn-favorite, .btn-favorite2, .btn-favorite3, .btn-favorite-catalog').classList.remove('active');
            }
            showNotification('Товар удален из избранного');
        }
    });
}

function toggleCompare(event, productId) {
    event.preventDefault();
    fetch('/comparisons/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => {
        if (response.status === 401) {
            showAuthModal();
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.status === 'added') {
            event.target.closest('.compare-btn').classList.add('active');
            showNotification('Товар добавлен к сравнению');
        } else if (data && data.status === 'removed') {
            event.target.closest('.compare-btn').classList.remove('active');
            showNotification('Товар удален из сравнения');
        } else if (data && data.status === 'error') {
            showNotification(data.message);
        }
    });
}

function addToCart(event, productId) {
    event.preventDefault();
    fetch('/add-to-cart/' + productId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.status === 401) {
            showAuthModal();
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            showNotification('Товар добавлен в корзину');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
} 