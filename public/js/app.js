/**
 * app.js — Global JS helpers, AJAX utilities, Toast notifications
 */
const APP = {
    baseUrl: '/JobPortal/JobPortal_Seeker/public',

    ajax(method, url, data = null) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open(method, this.baseUrl + url);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            if (data && !(data instanceof FormData)) {
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            }
            xhr.onload = () => {
                try {
                    const json = JSON.parse(xhr.responseText);
                    xhr.status >= 200 && xhr.status < 300 ? resolve(json) : reject(json);
                } catch(e) { reject({error: 'Invalid response'}); }
            };
            xhr.onerror = () => reject({error: 'Network error'});
            if (data instanceof FormData) { xhr.send(data); }
            else if (data) {
                const params = new URLSearchParams(data).toString();
                xhr.send(params);
            } else { xhr.send(); }
        });
    },

    get(url) { return this.ajax('GET', url); },
    post(url, data) { return this.ajax('POST', url, data); },

    toast(message, type = 'success', duration = 4000) {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(40px)';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    confirm(message) {
        return window.confirm(message);
    },

    debounce(fn, delay = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };
    },

    formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-US', {year:'numeric',month:'short',day:'numeric'});
    },

    formatSalary(min, max) {
        const fmt = n => new Intl.NumberFormat('en-US',{style:'currency',currency:'USD',maximumFractionDigits:0}).format(n);
        if (min && max) return `${fmt(min)} - ${fmt(max)}`;
        if (min) return `From ${fmt(min)}`;
        if (max) return `Up to ${fmt(max)}`;
        return 'Negotiable';
    }
};

// Auto-dismiss flash alerts
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Mobile sidebar toggle
    const toggler = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (toggler && sidebar) {
        toggler.addEventListener('click', () => sidebar.classList.toggle('open'));
    }
});
