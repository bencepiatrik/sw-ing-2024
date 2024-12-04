<template>
  <v-app>
    <v-app-bar app>
      <v-toolbar-title>Main Page</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn text href="/profile">Profile</v-btn>
      <v-btn text href="/">Landing</v-btn>
      <v-btn text @click="handleLogout">Logout</v-btn>
    </v-app-bar>
    <v-main>
      <v-container class="text-center">
        <v-card elevation="2" class="ma-4">
          <v-card-title class="headline">Welcome to the Main Page</v-card-title>
          <v-card-text>
            <p>You are now logged in.</p>
          </v-card-text>
        </v-card>
        <v-row>
          <v-col class="ma-4">
<!--@foreach($data as $i => $d)-->
            <v-expansion-panels>
              <v-expansion-panel class="my-2">
                <v-expansion-panel-title style="background-color: #e9efff">
                  <v-row no-gutters>
                    <v-col class="d-flex justify-center" cols="3" style="font-weight: bold">
                      TITLE
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3">
                      Category
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3" style="color: green; font-weight: bold" >
                      Status
                    </v-col>

                    <v-col class="d-flex justify-center" cols="3">
                      Uploaded
                    </v-col>
                  </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-row justify="start" no-gutters >

                      <v-col class="d-flex justify-center" cols="10"><v-expansion-panel-text>
                      There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                      </v-expansion-panel-text></v-col>
                    <v-col class="d-flex justify-center align-center" cols="2">
                      <v-btn color="primary">
                        Open
                      </v-btn>
                    </v-col>

                  </v-row>
                </v-expansion-panel-text>
              </v-expansion-panel>
<!--@endforeach-->
              <v-expansion-panel class="my-2">
                <v-expansion-panel-title style="background-color: #e9efff">
                  <v-row no-gutters>
                    <v-col class="d-flex justify-center" cols="3" style="font-weight: bold">
                      TITLE
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3">
                      Category
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3" style="color: green; font-weight: bold" >
                      Status
                    </v-col>

                    <v-col class="d-flex justify-center" cols="3">
                      Uploaded
                    </v-col>
                  </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-row justify="start" no-gutters >

                    <v-col class="d-flex justify-center" cols="10"><v-expansion-panel-text>
                      There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                    </v-expansion-panel-text></v-col>
                    <v-col class="d-flex justify-center align-center" cols="2">
                      <v-btn color="primary">
                        Open
                      </v-btn>
                    </v-col>

                  </v-row>
                </v-expansion-panel-text>
              </v-expansion-panel>
              <v-expansion-panel class="my-2">
                <v-expansion-panel-title style="background-color: #e9efff">
                  <v-row no-gutters>
                    <v-col class="d-flex justify-center" cols="3" style="font-weight: bold">
                      TITLE
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3">
                      Category
                    </v-col>
                    <v-col class="d-flex justify-center" cols="3" style="color: green; font-weight: bold" >
                      Status
                    </v-col>

                    <v-col class="d-flex justify-center" cols="3">
                      Uploaded
                    </v-col>
                  </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-row justify="start" no-gutters >

                    <v-col class="d-flex justify-center" cols="10"><v-expansion-panel-text>
                      There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                    </v-expansion-panel-text></v-col>
                    <v-col class="d-flex justify-center align-center" cols="2">
                      <v-btn color="primary">
                        Open
                      </v-btn>
                    </v-col>

                  </v-row>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { useAuthStore } from "../stores/authStore";

export default {
  setup() {
    const authStore = useAuthStore();

    const handleLogout = () => {
      authStore.logout();
      window.location.href = "/";
    };

    return {
      handleLogout,
    };
  },
};
</script>

<style scoped>

</style>
