<template>

    <table style="width:100vw; height:100vh;">
        <tbody>
        <tr>
            <td style="vertical-align: middle; text-align: center;">
                <section class="view">
                    <a href="https://tilda.cc"><img src="../assets/smiley_sadness.png" border="0" ></a>
                    <div class="description">
                        Что-то пошло не так. Попробуйте обновить страницу<br>
                        <a  @click="$router.back()" style="color: black;font-weight: 700;cursor: pointer; margin-right: 7px">Обновить</a>
                        <a  @click="$router.go(-2)" style="color: black;font-weight: 700;cursor: pointer">Назад</a>
                    </div>
                </section>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "n500"
    }
</script>

<style scoped>
    .view-title {
        font-size: 20px;
        color: #18224b;
        line-height: 1.23;
        font-family: 'PT Sans', Arial, sans-serif;
        font-weight: 600;
        padding: 20px 0 20px 0;
        text-align: center;
    }

    .description {
        text-align: center;
        padding-top: 20px;
        font-size: 14px;
        line-height: 1.55;
        font-family: 'PT Sans', Arial, sans-serif;
        font-weight: 400;
        color: #000;
    }
</style>