using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data;
using System.Data.Common;
using System.Data.SqlClient;
using System.Linq;
using System.Web;
using wealthTracker.DAL;

namespace wealthTracker.Models
{
    public class AppClient
    {
        private int id;
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int Id { get; set; }

        private string name;
        public string Name { get; set; }

        private string email;
        [Key]
        public string Email { get; set; }

        private bool isActive;
        public bool IsActive { get; set; }

        private bool superClient;
        public bool SuperClient { get; set; }

        WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();

        public static List<AppClient> GetAll(int currentPage, int pageSize, out int totalCount)
        {
            WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_AppClient_getAllPaged";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("currentPage", currentPage));
            cmd.Parameters.Add(new SqlParameter("pageSize", pageSize));
            var totalCountParam = new SqlParameter("totalCount", 0) { Direction = ParameterDirection.Output };
            cmd.Parameters.Add(totalCountParam);
            List<AppClient> clients;
            using (var reader = cmd.ExecuteReader())
            {
                clients = reader.MapToList<AppClient>();
            }

            //Access output variable after reader is closed
            totalCount = (totalCountParam.Value == null) ? 0 : Convert.ToInt32(totalCountParam.Value);

            return clients;
        }
    }
}