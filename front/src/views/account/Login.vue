<script setup>
import {ref, onMounted} from 'vue';
import {notifyError} from "@/composables/messages";
import http from "@/http";
import env from "@/env";
import {useAuthStore} from "@/stores/auth.js";
import {endLoading} from "@/composables/spinners";

// Data
const email = ref('');
const password = ref('');
const load = ref(false);
const authStore = useAuthStore();

// Methods
const login = async () => {
    load.value = true;
    const api = env.api + "login";

    try {
        await http.post(api, {
            email: email.value,
            password: password.value,
        });
        // Cookies are set automatically by the backend
        // No need to save token in localStorage
        authStore.initialize();
    } catch (error) {
        notifyError("Credenciais Inválidas");
    } finally {
        setTimeout(() => {
            load.value = false;
        }, 300);
    }
};

const showPass = () => {
    const passwordInput = document.getElementById('password-input');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
};

// Lifecycle
onMounted(() => {
    endLoading();
    if (window.location.href.indexOf('sistema.simplificagabinete') > -1) {
        email.value = '';
        password.value = '';
    } else if (window.location.href.indexOf('citygov') > -1) {
        email.value = 'contato@citygov.com.br';
    }
});
</script>

<template>
  <div class="auth-page-wrapper pt-5">
    <div
      id="auth-particles"
      class="auth-one-bg-position auth-one-bg bg-soft-primary"
    />

    <div class="auth-page-content">
      <b-container>
        <b-row class="justify-content-center">
          <b-col
            md="8"
            lg="6"
            xl="5"
          >
            <b-card no-body>
              <b-card-body class="p-4">
                <div class="text-center">
                  <img
                    src="@/assets/logos/logo-light.png"
                    alt="logo_libertas"
                    height="50"
                  >
                  <h5 class="mt-3">
                    Seja bem Vindo!
                  </h5>
                  <p class="text-muted">
                    Faça login para continuar.
                  </p>
                </div>
                <div class="p-2 mt-4">
                  <form @submit.prevent="login">
                    <div class="mb-3">
                      <label
                        for="email"
                        class="form-label"
                      >Email
                      </label>
                      <input
                        id="email"
                        v-model="email"
                        type="email"
                        class="form-control"
                        placeholder="Email de acesso"
                      >
                      <div class="invalid-feedback">
                        <span />
                      </div>
                    </div>

                    <div>
                      <label
                        class="form-label"
                        for="password-input"
                      >Senha
                      </label>
                      <div class="position-relative auth-pass-inputgroup mb-3">
                        <input
                          id="password-input"
                          v-model="password"
                          type="password"
                          class="form-control pe-5"
                          placeholder="Digite a Senha"
                        >
                        <b-button
                          id="password-addon"
                          variant="link"
                          class="position-absolute end-0 top-0 text-decoration-none text-muted"
                          type="button"
                          @click="showPass"
                        >
                          <i
                            id="icon-button"
                            class="ri-eye-fill align-middle"
                          />
                        </b-button>
                      </div>
                    </div>

                    <div class="mb-3 text-end">
                      <router-link to="/esqueceu-senha">
                        Esqueceu a senha?
                      </router-link>
                    </div>

                    <div class="mt-4">
                      <b-button
                        variant="soft-primary"
                        class="w-100 btn-load"
                        type="submit"
                      >
                        <span class="d-flex align-items-center margin-load">
                          <span
                            v-if="!load"
                            class="flex-grow-1"
                          >
                            Login
                          </span>
                          <span
                            v-else
                            class="spinner-border flex-shrink-0"
                            role="status"
                          >
                            <span class="visually-hidden" />
                          </span>
                        </span>
                      </b-button>
                    </div>
                  </form>
                </div>
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
    </div>

    <footer class="footer">
      <b-container>
        <b-row>
          <b-col lg="12">
            <div class="text-center">
              <p class="mb-0 text-muted">
                &copy; {{ new Date().getFullYear() }}
                <a
                  target="_blank"
                  class="text-primary"
                  href="https://lifecode.dev/"
                >LifeCode.
                </a>
                Feito com o
                <i class="mdi mdi-heart text-danger" />
              </p>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </footer>
  </div>
</template>

<style>

#auth-particles {
    height: 100%;
}


</style>
