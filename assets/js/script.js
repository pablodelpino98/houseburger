// Menú Mobile
function toggleMenu() {
  document.querySelector('.main-nav').classList.toggle('active');
}

// Carrito
function toggleCart() {
  const cartModal = document.getElementById('cartModal');
  cartModal.classList.toggle('show');

  if (cartModal.classList.contains('show')) {
    renderCart();
  }
}

window.onclick = function (event) {
  const cartModal = document.getElementById('cartModal');
  if (event.target === cartModal) {
    cartModal.classList.remove('show');
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modal');
  const modalMessage = document.getElementById('modal-message');
  const refrescoSelect = document.getElementById('refrescoSelect');
  const confirmComboBtn = document.getElementById('confirmCombo');
  const cancelComboBtn = document.getElementById('cancelCombo');
  const closeModalBtn = document.getElementById('closeModal');

  let currentProduct = null;

  const showModal = () => {
    modal.classList.add('show');
    modal.style.display = 'flex';
    refrescoSelect.style.display = 'none';
    confirmComboBtn.style.display = 'inline-block';
    cancelComboBtn.style.display = 'inline-block';
    closeModalBtn.style.display = 'none';
    modalMessage.textContent = '¿Desea añadir Papas Fritas Clásicas y un Refresco por 3,00€?';
  };

  const closeModal = () => {
    modal.classList.remove('show');
    modal.style.display = 'none';
    currentProduct = null;
  };

  const addToCart = (product) => {
    fetch('../cart/add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(product)
    })
      .then(res => res.json())
      .then(() => {
        renderCart();
      })
      .catch(err => console.error('Error al añadir al carrito:', err));
  };

  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', (e) => {
      const productCard = e.target.closest('.product-card');
      const product = {
        product_id: parseInt(productCard.getAttribute('data-id')),
        name: productCard.getAttribute('data-name'),
        price: parseFloat(productCard.getAttribute('data-price')),
        type: productCard.getAttribute('data-type')
      };

      currentProduct = product;

      product.type === 'hamburguesa' ? showModal() : addToCart(product);
    });
  });

  cancelComboBtn.addEventListener('click', () => {
    addToCart(currentProduct);
    modalMessage.textContent = 'Añadido al carrito';
    refrescoSelect.style.display = 'none';
    confirmComboBtn.style.display = 'none';
    cancelComboBtn.style.display = 'none';
    closeModalBtn.style.display = 'inline-block';
  });

  confirmComboBtn.addEventListener('click', () => {
    if (refrescoSelect.style.display === 'none') {
      refrescoSelect.style.display = 'inline-block';
      modalMessage.textContent = 'Seleccione el refresco para tu combo y confirma:';
      confirmComboBtn.textContent = 'Añadir combo';
      cancelComboBtn.style.display = 'none';
    } else {
      const refresco = refrescoSelect.value;
      const combo = {
        product_id: currentProduct.product_id, // usa el mismo ID
        name: `${currentProduct.name} + Papas Fritas Clásicas + ${refresco}`,
        price: currentProduct.price + 3.00,
        type: 'combo'
      };
      addToCart(combo);
      modalMessage.textContent = 'Añadido al carrito';
      refrescoSelect.style.display = 'none';
      confirmComboBtn.style.display = 'none';
      closeModalBtn.style.display = 'inline-block';
    }
  });

  closeModalBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });
});

// Renderizar carrito desde PHP sesión
function renderCart() {
  fetch('../cart/get_cart.php')
    .then(res => res.json())
    .then(cart => {
      const cartContent = document.getElementById('cart-content');
      const totalElement = document.getElementById('total');
      const cartDataInput = document.getElementById('cart_data_input');

      if (!cartContent || !totalElement) return;

      if (cart.length === 0) {
        cartContent.innerHTML = '<p>El carrito está vacío.</p>';
        totalElement.textContent = 'Total: 0.00 €';
        if (cartDataInput) cartDataInput.value = '';
        return;
      }

      cartContent.innerHTML = '';
      let total = 0;

      cart.forEach((item, index) => {
        const div = document.createElement('div');
        div.classList.add('cart-item');
        div.innerHTML = `
          <p><strong>${item.name}</strong> - ${item.price.toFixed(2)} €</p>
          <button onclick="removeFromCart(${index})">Eliminar</button>
        `;
        cartContent.appendChild(div);
        total += parseFloat(item.price);
      });

      totalElement.textContent = `Total: ${total.toFixed(2)} €`;
      if (cartDataInput) cartDataInput.value = JSON.stringify(cart);
    })
    .catch(error => {
      console.error('Error al renderizar carrito:', error);
    });
}

// Eliminar un producto por índice
function removeFromCart(index) {
  fetch('../cart/remove_from_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ index })
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') {
        renderCart();
      } else {
        console.error('Error al eliminar:', data.message);
      }
    })
    .catch(err => console.error('Error al eliminar producto:', err));
}

// Vaciar carrito completamente
function clearCart() {
  fetch('../cart/clear_cart.php')
    .then(res => res.json())
    .then(() => renderCart())
    .catch(err => console.error('Error al vaciar carrito:', err));
}
