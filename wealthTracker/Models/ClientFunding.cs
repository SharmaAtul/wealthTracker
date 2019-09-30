using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data;
using System.Data.Common;
using System.Data.SqlClient;
using System.Linq;
using System.Web;
using wealthTracker.Controllers;
using wealthTracker.DAL;

namespace wealthTracker.Models
{
    public class ClientFunding
    {
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int ClientFundingID { get; set; }
        [Key]
        [Column(Order = 0)]
        public string UserEmail { get; set; }
        [Key]
        [Column(Order = 1)]
        public int FundingYear { get; set; }
        [Key]
        [Column(Order = 2)]
        public int FundingMonth { get; set; }
        public string RiskProfile { get; set; }
        public decimal FinancialDependencyAge { get; set; }

        [JsonConverter(typeof(CustomDateTimeConverter))]
        public DateTime? FundingPeriod { get; set; }
        public decimal SumLeft { get; set; }
        public decimal ExpectedAusShares { get; set; }
        public decimal ExpectedIntShares { get; set; }
        public decimal ExpectedProperty { get; set; }
        public decimal ExpectedAusFixedInterest { get; set; }
        public decimal ExpectedIntFixedInterest { get; set; }
        public decimal ExpectedCash { get; set; }
        public decimal ExpectedAttractiveAssets { get; set; }
        public decimal ActualAusShares { get; set; }
        public decimal ActualIntShares { get; set; }
        public decimal ActualProperty { get; set; }
        public decimal ActualAusFixedInterest { get; set; }
        public decimal ActualIntFixedInterest { get; set; }
        public decimal ActualCash { get; set; }
        public decimal ActualAttractiveAssets { get; set; }
        public decimal? PersonalAssets { get; set; }
        public decimal? SuperAssets { get; set; }
        public decimal? PensionAssets { get; set; }
        public decimal? OtherEntitiesAssets { get; set; }
        public decimal TotalAssets { get; set; }
        [JsonConverter(typeof(CustomDateTimeConverter))]
        public DateTime? EntryDate { get; set; }
        public decimal FinancialIndependencyAge { get; set; }

        [JsonConverter(typeof(CustomDateTimeConverter))]
        public DateTime? LifestyleObjectiveAt { get; set; }
        public decimal AnnualLeavingOff { get; set; }
        public decimal LastingFor { get; set; }
        public decimal LeaveForState { get; set; }
        public string AlternativeAssetDetails { get; set; }
        public string PersonalAssetDetails { get; set; }
        public string SuperAssetDetails { get; set; }
        public string PensionAssetDetails { get; set; }
        public string OtherEntitiesAssetDetails { get; set; }

        [JsonConverter(typeof(CustomDateTimeConverter))]
        public DateTime? LastProjectionDate { get; set; }
        public string Conclusion1 { get; set; }
        public string Conclusion2 { get; set; }
        public string Conclusion3 { get; set; }

        WealthTrackerIdentityDB db = new WealthTrackerIdentityDB();
        public ClientFunding GetLatestClientFunding(string userEmail)
        {
            if(db.Database.Connection.State== ConnectionState.Closed)
                db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ClientFunding_GetLatest";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("userEmail", userEmail));

            ClientFunding funding;
            using (var reader = cmd.ExecuteReader())
            {
                if (reader.HasRows)
                    funding = reader.MapToList<ClientFunding>()[0];
                else
                    funding = new ClientFunding();
            }

            return funding;
        }

        public ClientFunding GetByFundingId(int fundingId)
        {
            if (db.Database.Connection.State == ConnectionState.Closed)
                db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ClientFunding_GetByFundingId";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("fundingId", fundingId));

            ClientFunding funding;
            using (var reader = cmd.ExecuteReader())
            {
                if (reader.HasRows)
                    funding = reader.MapToList<ClientFunding>()[0];
                else
                    funding = new ClientFunding();
            }

            return funding;
        }

        
        public Dictionary<string, List<DashboardReportData>> GetAssetClassesReportData(int userId)
        {
            if (db.Database.Connection.State == ConnectionState.Closed)
                db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ClientFunding_AssetClassesReportData";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("userId", userId));

            DataTable dtReportData = new DataTable();
            dtReportData.Load(cmd.ExecuteReader());

            List<DashboardReportData> oReportData = new List<DashboardReportData>();
            List<DashboardReportData> oReportDataGrowth = new List<DashboardReportData>();
            DashboardReportData ActualGrowth = new DashboardReportData
            {
                color = "",
                ApplyOrder = "1",
                key = "Actual Growth Exposure",
                values = new List<List<object>>()
            };
            
            DashboardReportData ExpectedGrowth = new DashboardReportData
            {
                color = "",
                ApplyOrder = "2",
                key = "Expected Growth Exposure",
                values = new List<List<object>>()
            };
            

            Dictionary<string, List<DashboardReportData>> oCompleteAssetReportData = new Dictionary<string, List<DashboardReportData>>();
            
            DashboardReportData oData = new DashboardReportData();

            foreach (DataRow dr in dtReportData.Rows)
            {
                oData = oReportData.Find(i => i.key == dr["key"].ToString());
                if (oData == null)
                {
                    oData = new DashboardReportData();
                    oReportDataGrowth = new List<DashboardReportData>();
                    oData.ApplyOrder = dr["ApplyOrder"].ToString();
                    oData.key = dr["Key"].ToString();
                    oData.values = new List<List<object>>();
                    oReportData.Add(oData);
                }
                List<object> value = new List<object>();
                List<object> actualGrowth = new List<object>();
                List<object> expectedGrowth = new List<object>();

                if (oData.key == "Property")
                {
                    actualGrowth.Add(double.Parse(dr["FundingYear"].ToString()));
                    actualGrowth.Add(double.Parse(dr["ActualGrowth"].ToString()));
                    ActualGrowth.values.Add(actualGrowth);

                    expectedGrowth.Add(double.Parse(dr["FundingYear"].ToString()));
                    expectedGrowth.Add(double.Parse(dr["ExpectedGrowth"].ToString()));
                    ExpectedGrowth.values.Add(expectedGrowth);
                }
                
                value.Add(double.Parse(dr["FundingYear"].ToString()));
                value.Add(double.Parse(dr["Value"].ToString()));
                oData.values.Add(value);
            }

            //ActualGrowth.color = "#a9bb82";
            //ExpectedGrowth.color = "#dde5a8";

            oReportDataGrowth.Add(ActualGrowth);
            oReportDataGrowth.Add(ExpectedGrowth);

            oCompleteAssetReportData.Add("All", oReportData);
            oCompleteAssetReportData.Add("Growth", oReportDataGrowth);

            return oCompleteAssetReportData;
        }

        public Dictionary<string, List<DashboardReportData>> GetNetWealthReportData(int userId)
        {
            if (db.Database.Connection.State == ConnectionState.Closed)
                db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ClientFunding_NetWealthReportData";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("userId", userId));

            DataTable dtReportData = new DataTable();
            dtReportData.Load(cmd.ExecuteReader());

            Dictionary<string, List<DashboardReportData>> oCompleteWealthReportData = new Dictionary<string, List<DashboardReportData>>();

            List<DashboardReportData> oReportData = new List<DashboardReportData>();
            List<DashboardReportData> oReportDataWealth = new List<DashboardReportData>();
            DashboardReportData ActualWealth = new DashboardReportData
            {
                color = "",
                ApplyOrder = "1",
                key = "Actual Wealth",
                values = new List<List<object>>()
            };
            
            DashboardReportData ExpectedWealth = new DashboardReportData
            {
                color = "",
                ApplyOrder = "2",
                key = "Expected Wealth",
                values = new List<List<object>>()
            };
            

            DashboardReportData oData = new DashboardReportData();

            foreach (DataRow dr in dtReportData.Rows)
            {
                oData = oReportData.Find(i => i.key == dr["key"].ToString());
                if (oData == null)
                {
                    oData = new DashboardReportData();
                    oData.key = dr["Key"].ToString();
                    oData.values = new List<List<object>>();
                    oReportData.Add(oData);
                }

                List<object> value = new List<object>();
                List<object> actualWealth = new List<object>();
                List<object> expectedWealth = new List<object>();

                if (oData.key == "Personal Assets")
                {
                    if (dr["OnlyProposed"].ToString() == "0")
                    {
                        actualWealth.Add(double.Parse(dr["FundingYear"].ToString()));
                        actualWealth.Add(double.Parse(dr["ActualAsset"].ToString()));
                        ActualWealth.values.Add(actualWealth);
                    }
                    else {
                        actualWealth.Add(double.Parse(dr["FundingYear"].ToString()));
                        actualWealth.Add(null);
                        ActualWealth.values.Add(actualWealth);
                    }

                    if (dr["ExpectedAsset"].ToString() != "")
                    {
                        expectedWealth.Add(double.Parse(dr["FundingYear"].ToString()));
                        expectedWealth.Add(dr["ExpectedAsset"].ToString() == "" ? null : dr["ExpectedAsset"].ToString());
                        ExpectedWealth.values.Add(expectedWealth);
                    }
                }

                if (dr["OnlyProposed"].ToString() == "0")
                {
                    value.Add(double.Parse(dr["FundingYear"].ToString()));
                    if (dr["Value"].ToString() == "")
                        value.Add(0);
                    else
                        value.Add(double.Parse(dr["Value"].ToString()));
                    oData.values.Add(value);
                }
            }

            //ActualWealth.color = "#a9bb82";
            //ExpectedWealth.color = "#3d7475";

            oReportDataWealth.Add(ActualWealth);
            oReportDataWealth.Add(ExpectedWealth);

            oCompleteWealthReportData.Add("All", oReportData);
            oCompleteWealthReportData.Add("Net", oReportDataWealth);

            return oCompleteWealthReportData;
        }

        public List<ClientFunding> GetAll(int clientId, int currentPage, int pageSize, out int totalCount)
        {
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ClientFunding_GetAllPagedByClientID";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("clientID", clientId));
            cmd.Parameters.Add(new SqlParameter("currentPage", currentPage));
            cmd.Parameters.Add(new SqlParameter("pageSize", pageSize));
            var totalCountParam = new SqlParameter("totalCount", 0) { Direction = ParameterDirection.Output };
            cmd.Parameters.Add(totalCountParam);

            List<ClientFunding> fundings;
            using (var reader = cmd.ExecuteReader())
            {
                if (reader.HasRows)
                    fundings = reader.MapToList<ClientFunding>();
                else
                    fundings = new List<ClientFunding>();
            }

            totalCount = (totalCountParam.Value == null) ? 0 : Convert.ToInt32(totalCountParam.Value);

            return fundings;
        }

        public bool Delete(int fundingId)
        {
            db.Database.Connection.Open();

            DbCommand cmd = db.Database.Connection.CreateCommand();
            cmd.CommandText = "proc_ClientFunding_Delete";
            cmd.CommandType = System.Data.CommandType.StoredProcedure;
            cmd.Parameters.Add(new SqlParameter("fundingId", fundingId));

            int result = cmd.ExecuteNonQuery();
            return result > 0;
        }

    }
}