function scrollToRow(i) {
  var tabela = document.getElementById("minhaTabela");
  var linhas = tabela.getElementsByTagName("tr");

  linhas[i].classList.toggle("selecionado");

  setTimeout(function() {
    linhas[i].scrollIntoView();
  }, 100);
}