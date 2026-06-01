var builder = WebApplication.CreateBuilder(args);

// 1. Libera o CORS para qualquer site conseguir consultar sua API
builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", policy =>
    {
        policy.AllowAnyOrigin().AllowAnyMethod().AllowAnyHeader();
    });
});

builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();

var app = builder.Build();

app.UseHttpsRedirection();

// 2. Ativa o CORS antes dos controladores
app.UseCors("AllowAll");

app.UseAuthorization();
app.MapControllers();

app.Run();