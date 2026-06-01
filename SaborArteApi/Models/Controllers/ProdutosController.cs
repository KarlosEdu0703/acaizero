using Microsoft.AspNetCore.Mvc;
using MySql.Data.MySqlClient;
using SaborArteApi.Models;

namespace SaborArteApi.Controllers;

[ApiController]
[Route("api/[controller]")] // Rota: http://localhost:5000/api/produtos
public class ProdutosController : ControllerBase
{
    // Conexão padrão do seu XAMPP
    private readonly string _connectionString = "Server=localhost;Database=sabor_arte;Uid=root;Pwd=;";

    [HttpGet]
    public IActionResult GetProdutos()
    {
        var produtos = new List<Produto>();

        using (var connection = new MySqlConnection(_connectionString))
        {
            connection.Open();
            var query = "SELECT * FROM produtos";
            
            using (var command = new MySqlCommand(query, connection))
            using (var reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    produtos.Add(new Produto
                    {
                        Id = reader.GetInt32("id"),
                        Nome = reader.GetString("nome"),
                        Descricao = reader.IsDBNull(reader.GetOrdinal("descricao")) ? null : reader.GetString("descricao"),
                        Preco = reader.GetDecimal("preco"),
                        Categoria = reader.GetString("categoria"),
                        Tag = reader.IsDBNull(reader.GetOrdinal("tag")) ? null : reader.GetString("tag"),
                        Imagem = reader.IsDBNull(reader.GetOrdinal("imagem")) ? null : reader.GetString("imagem")
                    });
                }
            }
        }
        return Ok(produtos);
    }
}